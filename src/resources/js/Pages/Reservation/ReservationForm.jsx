import { useEffect, useMemo, useState } from "react";
import Calendar from "react-calendar";
import "react-calendar/dist/Calendar.css";
import "../../../css/pages/reservation/reservation-form.css";

function pad2(n) {
    return String(n).padStart(2, "0");
}

function formatDateYMD(d) {
    return `${d.getFullYear()}-${pad2(d.getMonth() + 1)}-${pad2(d.getDate())}`;
}

function getWeekOfMonthLikeLaravel(d) {
    const day = d.getDate();
    const first = new Date(d.getFullYear(), d.getMonth(), 1);
    const firstIso = first.getDay() === 0 ? 7 : first.getDay();
    return Math.ceil((day + firstIso - 1) / 7);
}

function generateTimeSlots(start, end, interval = 30) {
    const slots = [];
    if (!start || !end) return slots;

    let [hour, minute] = String(start).split(":").map(Number);
    const [endHour, endMinute] = String(end).split(":").map(Number);

    while (hour < endHour || (hour === endHour && minute <= endMinute)) {
        slots.push(`${pad2(hour)}:${pad2(minute)}`);
        minute += interval;
        if (minute >= 60) {
            hour += 1;
            minute -= 60;
        }
    }

    return slots;
}

const PARTY_OPTIONS = [
    { value: 1, label: "1名" },
    { value: 2, label: "2名" },
    { value: 3, label: "3名" },
    { value: 4, label: "4名" },
    { value: 5, label: "5名" },
    { value: 6, label: "6名" },
    { value: 7, label: "7名" },
    { value: 8, label: "8名" },
    { value: 10, label: "10名以上（宴会）" },
];

const STEP_TITLES = [
    "日付選択",
    "人数選択",
    "時間帯選択",
    "席タイプ希望",
    "お客様情報",
    "備考",
    "確認・送信",
];

export default function ReservationForm() {
    const tomorrow = useMemo(() => {
        const d = new Date();
        d.setHours(0, 0, 0, 0);
        d.setDate(d.getDate() + 1);
        return d;
    }, []);

    const [step, setStep] = useState(1);
    const [date, setDate] = useState(tomorrow);
    const [selectedTime, setSelectedTime] = useState("");
    const [availableTimes, setAvailableTimes] = useState([]);
    const [availableSlots, setAvailableSlots] = useState([]);
    const [availabilityLoading, setAvailabilityLoading] = useState(false);
    const [businessHours, setBusinessHours] = useState([]);

    const [formData, setFormData] = useState({
        party_size: 1,
        seat_preference: "",
        name: "",
        email: "",
        phone: "",
        notes: "",
    });

    const [fieldErrors, setFieldErrors] = useState({});
    const [message, setMessage] = useState("");

    useEffect(() => {
        async function fetchBusinessHours() {
            try {
                const year = date.getFullYear();
                const month = date.getMonth() + 1;
                const res = await fetch(`/api/business-hours/weekly?year=${year}&month=${month}`);
                if (!res.ok) return;
                const data = await res.json();
                setBusinessHours(Array.isArray(data) ? data : []);
            } catch {
                setBusinessHours([]);
            }
        }
        fetchBusinessHours();
    }, [date]);

    useEffect(() => {
        if (!Array.isArray(businessHours) || businessHours.length === 0) {
            setAvailableTimes([]);
            return;
        }

        const dayOfWeekNames = ["日", "月", "火", "水", "木", "金", "土"];
        const selectedDay = dayOfWeekNames[date.getDay()];
        const weekOfMonth = getWeekOfMonthLikeLaravel(date);

        const hourInfo = businessHours.find(
            (h) => Number(h.week_of_month) === Number(weekOfMonth) && h.day_of_week === selectedDay
        );

        if (!hourInfo || hourInfo.is_closed) {
            setAvailableTimes([]);
            return;
        }

        setAvailableTimes(generateTimeSlots(hourInfo.open_time, hourInfo.close_time, 30));
    }, [date, businessHours]);

    useEffect(() => {
        if (!formData.party_size || formData.party_size >= 10) {
            setAvailableSlots([]);
            return;
        }

        const controller = new AbortController();

        async function fetchAvailability() {
            setAvailabilityLoading(true);
            try {
                const ymd = formatDateYMD(date);
                const res = await fetch(
                    `/api/reservations/check?date=${encodeURIComponent(ymd)}&party_size=${encodeURIComponent(
                        formData.party_size
                    )}`,
                    { signal: controller.signal }
                );
                const data = await res.json().catch(() => ({}));

                if (!res.ok) {
                    setAvailableSlots([]);
                    return;
                }

                const slots = Array.isArray(data.available_slots) ? data.available_slots : [];
                setAvailableSlots(slots);

                if (selectedTime) {
                    const starts = new Set(slots.map((s) => s.start));
                    if (!starts.has(selectedTime)) setSelectedTime("");
                }
            } catch (err) {
                if (err?.name !== "AbortError") setAvailableSlots([]);
            } finally {
                setAvailabilityLoading(false);
            }
        }

        fetchAvailability();
        return () => controller.abort();
    }, [date, formData.party_size, selectedTime]);

    const availableStartSet = useMemo(() => new Set(availableSlots.map((s) => s.start)), [availableSlots]);

    const tileDisabled = ({ date: tileDate }) => {
        if (tileDate < tomorrow) return true;

        if (!Array.isArray(businessHours) || businessHours.length === 0) return false;

        const dayOfWeekNames = ["日", "月", "火", "水", "木", "金", "土"];
        const selectedDay = dayOfWeekNames[tileDate.getDay()];
        const weekOfMonth = getWeekOfMonthLikeLaravel(tileDate);

        const dayInfo = businessHours.find(
            (h) => Number(h.week_of_month) === Number(weekOfMonth) && h.day_of_week === selectedDay
        );

        return !dayInfo || !!dayInfo.is_closed;
    };

    const handleNext = () => {
        if (step === 2 && formData.party_size >= 10) return;
        setStep((s) => Math.min(7, s + 1));
    };

    const handleBack = () => setStep((s) => Math.max(1, s - 1));

    const handleSubmit = async (e) => {
        e.preventDefault();
        setFieldErrors({});
        setMessage("");

        if (!selectedTime) {
            setMessage("時間帯を選択してください。");
            setStep(3);
            return;
        }

        const payload = {
            date: formatDateYMD(date),
            start_time: selectedTime,
            party_size: Number(formData.party_size),
            seat_preference: formData.seat_preference || null,
            service_id: null,
            name: formData.name,
            email: formData.email,
            phone: formData.phone,
            notes: formData.notes || null,
        };

        try {
            const response = await fetch("/api/reservations", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json().catch(() => ({}));

            if (response.ok) {
                setMessage("ご予約を受け付けました。確認メールをご確認ください。");
                setStep(1);
                setSelectedTime("");
                setFormData({
                    party_size: 1,
                    seat_preference: "",
                    name: "",
                    email: "",
                    phone: "",
                    notes: "",
                });
                return;
            }

            if (data.errors) {
                const errs = {};
                Object.keys(data.errors).forEach((key) => {
                    errs[key] = Array.isArray(data.errors[key]) ? data.errors[key][0] : data.errors[key];
                });
                setFieldErrors(errs);
            }
            setMessage(data.message || "予約に失敗しました。");
        } catch {
            setMessage("サーバー通信エラーが発生しました。");
        }
    };

    return (
        <main className="reservation-main">
            <h1 className="reservation-title">テーブル予約</h1>
            <p className="reservation-subtitle">Step {step}/7: {STEP_TITLES[step - 1]}</p>

            <form onSubmit={handleSubmit} className="reservation-form-card" noValidate>
                {step === 1 ? (
                    <div className="reservation-field">
                        <label className="reservation-label">Step 1: ご希望日</label>
                        <div className="reservation-calendar-wrapper">
                            <div className="reservation-calendar">
                                <Calendar onChange={(d) => setDate(Array.isArray(d) ? d[0] : d)} value={date} minDate={tomorrow} tileDisabled={tileDisabled} />
                            </div>
                            <p className="reservation-date-text">選択日: {date.toLocaleDateString()}</p>
                        </div>
                    </div>
                ) : null}

                {step === 2 ? (
                    <div className="reservation-field">
                        <label className="reservation-label">Step 2: ご利用人数</label>
                        <select
                            name="party_size"
                            value={formData.party_size}
                            onChange={(e) => {
                                const value = Number(e.target.value);
                                setFormData((p) => ({ ...p, party_size: value }));
                                setSelectedTime("");
                            }}
                            className="reservation-select"
                        >
                            {PARTY_OPTIONS.map((opt) => (
                                <option key={opt.value} value={opt.value}>
                                    {opt.label}
                                </option>
                            ))}
                        </select>

                        {formData.party_size >= 5 && formData.party_size <= 8 ? (
                            <p className="reservation-time-note">座敷席（2卓結合）をご用意いたします。</p>
                        ) : null}

                        {formData.party_size >= 10 ? (
                            <div className="reservation-banquet-box">
                                <p>10名以上は宴会予約をご利用ください。</p>
                                <button type="button" className="reservation-submit-button" onClick={() => (window.location.href = "/banquet-inquiry")}>宴会のご予約はこちら</button>
                            </div>
                        ) : null}
                        {fieldErrors.party_size && <p className="reservation-field-error">{fieldErrors.party_size}</p>}
                    </div>
                ) : null}

                {step === 3 ? (
                    <div className="reservation-field">
                        <label className="reservation-label">Step 3: ご希望時間（○/×）</label>
                        <div className="reservation-time-wrapper">
                            {availableTimes.length === 0 ? (
                                <p className="reservation-time-note">この日は休業日または営業時間外です。</p>
                            ) : availabilityLoading ? (
                                <p className="reservation-time-note">空き状況を確認中...</p>
                            ) : (
                                <div className="reservation-time-grid">
                                    {availableTimes.map((time) => {
                                        const isAvailable = availableStartSet.has(time);
                                        return (
                                            <button
                                                type="button"
                                                key={time}
                                                onClick={() => isAvailable && setSelectedTime(time)}
                                                disabled={!isAvailable}
                                                className={`reservation-time-button ${selectedTime === time ? "reservation-time-button--selected" : ""} ${!isAvailable ? "reservation-time-button--disabled" : ""}`}
                                            >
                                                <span className="reservation-time-label">{time}</span>
                                                <span className="reservation-time-status">{isAvailable ? "○" : "×"}</span>
                                            </button>
                                        );
                                    })}
                                </div>
                            )}
                        </div>
                        {selectedTime ? <p className="reservation-selected-time">選択時間: {selectedTime}</p> : null}
                    </div>
                ) : null}

                {step === 4 ? (
                    <div className="reservation-field">
                        <label className="reservation-label">Step 4: 席タイプ希望（任意）</label>
                        <select
                            className="reservation-select"
                            value={formData.seat_preference}
                            onChange={(e) => setFormData((p) => ({ ...p, seat_preference: e.target.value }))}
                        >
                            <option value="">指定なし</option>
                            <option value="regular">テーブル席</option>
                            <option value="private">個室</option>
                            <option value="tatami">座敷</option>
                        </select>
                    </div>
                ) : null}

                {step === 5 ? (
                    <>
                        <div className="reservation-field">
                            <label className="reservation-label">Step 5: お名前</label>
                            <input className={`reservation-input ${fieldErrors.name ? "reservation-input--error" : ""}`} value={formData.name} onChange={(e) => setFormData((p) => ({ ...p, name: e.target.value }))} />
                            {fieldErrors.name && <p className="reservation-field-error">{fieldErrors.name}</p>}
                        </div>
                        <div className="reservation-field">
                            <label className="reservation-label">メールアドレス</label>
                            <input type="email" className={`reservation-input ${fieldErrors.email ? "reservation-input--error" : ""}`} value={formData.email} onChange={(e) => setFormData((p) => ({ ...p, email: e.target.value }))} />
                            {fieldErrors.email && <p className="reservation-field-error">{fieldErrors.email}</p>}
                        </div>
                        <div className="reservation-field">
                            <label className="reservation-label">電話番号</label>
                            <input className={`reservation-input ${fieldErrors.phone ? "reservation-input--error" : ""}`} value={formData.phone} onChange={(e) => setFormData((p) => ({ ...p, phone: e.target.value }))} />
                            {fieldErrors.phone && <p className="reservation-field-error">{fieldErrors.phone}</p>}
                        </div>
                    </>
                ) : null}

                {step === 6 ? (
                    <div className="reservation-field">
                        <label className="reservation-label">Step 6: 備考</label>
                        <textarea className="reservation-textarea" rows={4} value={formData.notes} onChange={(e) => setFormData((p) => ({ ...p, notes: e.target.value }))} />
                    </div>
                ) : null}

                {step === 7 ? (
                    <div className="reservation-field">
                        <label className="reservation-label">Step 7: 確認</label>
                        <div className="reservation-confirm">
                            <p>日付: {formatDateYMD(date)}</p>
                            <p>人数: {formData.party_size}名</p>
                            <p>時間: {selectedTime || "未選択"}</p>
                            <p>席希望: {formData.seat_preference || "指定なし"}</p>
                            <p>お名前: {formData.name}</p>
                            <p>メール: {formData.email}</p>
                            <p>電話: {formData.phone}</p>
                        </div>
                    </div>
                ) : null}

                {message ? <p className={`reservation-message ${fieldErrors && Object.keys(fieldErrors).length ? "reservation-message--error" : "reservation-message--success"}`}>{message}</p> : null}

                <div className="reservation-step-actions">
                    {step > 1 ? (
                        <button type="button" className="reservation-back-button" onClick={handleBack}>
                            戻る
                        </button>
                    ) : null}
                    {step < 7 ? (
                        <button type="button" className="reservation-submit-button" onClick={handleNext} disabled={step === 2 && formData.party_size >= 10}>
                            次へ
                        </button>
                    ) : (
                        <button type="submit" className="reservation-submit-button">
                            予約を送信する
                        </button>
                    )}
                </div>
            </form>

            <div className="reservation-phone-notice">
                <p>当日のご予約はお電話にて承ります</p>
                <a href="tel:08097049500">080-9704-9500</a>
            </div>
        </main>
    );
}
