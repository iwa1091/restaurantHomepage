import { useState } from "react";
import { Head, Link } from "@inertiajs/react";
import "../../../css/pages/reservation/reservation-form.css";

export default function BanquetInquiryForm() {
    const [form, setForm] = useState({
        name: "",
        email: "",
        phone: "",
        party_size: 10,
        preferred_date: "",
        preferred_time: "",
        budget_per_person: "",
        course_preference: "",
        notes: "",
    });
    const [message, setMessage] = useState("");
    const [errors, setErrors] = useState({});

    const minDate = new Date();
    minDate.setDate(minDate.getDate() + 7);
    const minYmd = `${minDate.getFullYear()}-${String(minDate.getMonth() + 1).padStart(2, "0")}-${String(minDate.getDate()).padStart(2, "0")}`;

    const submit = async (e) => {
        e.preventDefault();
        setErrors({});
        setMessage("");
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "";

        const res = await fetch("/banquet-inquiry", {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": csrf,
            },
            body: JSON.stringify(form),
        });

        const data = await res.json().catch(() => ({}));
        if (res.ok) {
            setMessage("宴会のお問い合わせを受け付けました。店舗より折り返しご連絡いたします。");
            setForm({
                name: "",
                email: "",
                phone: "",
                party_size: 10,
                preferred_date: "",
                preferred_time: "",
                budget_per_person: "",
                course_preference: "",
                notes: "",
            });
            return;
        }

        if (data.errors) {
            const next = {};
            Object.keys(data.errors).forEach((k) => {
                next[k] = Array.isArray(data.errors[k]) ? data.errors[k][0] : data.errors[k];
            });
            setErrors(next);
        }
        setMessage(data.message || "送信に失敗しました。");
    };

    return (
        <main className="reservation-main">
            <Head title="宴会・団体お問い合わせ" />
            <h1 className="reservation-title">宴会・団体のご予約</h1>
            <p className="reservation-subtitle">ご予約は店舗からのご連絡をもって確定となります。</p>

            <form className="reservation-form-card" onSubmit={submit}>
                <div className="reservation-field">
                    <label className="reservation-label">お名前（必須）</label>
                    <input className="reservation-input" value={form.name} onChange={(e) => setForm((p) => ({ ...p, name: e.target.value }))} />
                    {errors.name ? <p className="reservation-field-error">{errors.name}</p> : null}
                </div>
                <div className="reservation-field">
                    <label className="reservation-label">メールアドレス（必須）</label>
                    <input type="email" className="reservation-input" value={form.email} onChange={(e) => setForm((p) => ({ ...p, email: e.target.value }))} />
                    {errors.email ? <p className="reservation-field-error">{errors.email}</p> : null}
                </div>
                <div className="reservation-field">
                    <label className="reservation-label">電話番号（必須）</label>
                    <input className="reservation-input" value={form.phone} onChange={(e) => setForm((p) => ({ ...p, phone: e.target.value }))} />
                    {errors.phone ? <p className="reservation-field-error">{errors.phone}</p> : null}
                </div>
                <div className="reservation-field">
                    <label className="reservation-label">ご利用人数（10名〜）</label>
                    <input type="number" min={10} className="reservation-input" value={form.party_size} onChange={(e) => setForm((p) => ({ ...p, party_size: Number(e.target.value) }))} />
                    {errors.party_size ? <p className="reservation-field-error">{errors.party_size}</p> : null}
                </div>
                <div className="reservation-field">
                    <label className="reservation-label">ご希望日（1週間後以降）</label>
                    <input type="date" min={minYmd} className="reservation-input" value={form.preferred_date} onChange={(e) => setForm((p) => ({ ...p, preferred_date: e.target.value }))} />
                    {errors.preferred_date ? <p className="reservation-field-error">{errors.preferred_date}</p> : null}
                </div>
                <div className="reservation-field">
                    <label className="reservation-label">ご希望時間帯（任意）</label>
                    <select className="reservation-select" value={form.preferred_time} onChange={(e) => setForm((p) => ({ ...p, preferred_time: e.target.value }))}>
                        <option value="">指定なし</option>
                        <option value="ランチ">ランチ</option>
                        <option value="ディナー">ディナー</option>
                    </select>
                </div>
                <div className="reservation-field">
                    <label className="reservation-label">お一人様ご予算（任意）</label>
                    <select className="reservation-select" value={form.budget_per_person} onChange={(e) => setForm((p) => ({ ...p, budget_per_person: e.target.value }))}>
                        <option value="">指定なし</option>
                        <option value="3000">3,000円</option>
                        <option value="4000">4,000円</option>
                        <option value="5000">5,000円</option>
                        <option value="6000">6,000円</option>
                        <option value="相談したい">相談したい</option>
                    </select>
                </div>
                <div className="reservation-field">
                    <label className="reservation-label">コース・料理のご希望（任意）</label>
                    <textarea className="reservation-textarea" rows={3} value={form.course_preference} onChange={(e) => setForm((p) => ({ ...p, course_preference: e.target.value }))} />
                </div>
                <div className="reservation-field">
                    <label className="reservation-label">備考・アレルギー等（任意）</label>
                    <textarea className="reservation-textarea" rows={3} value={form.notes} onChange={(e) => setForm((p) => ({ ...p, notes: e.target.value }))} />
                </div>

                <div className="reservation-confirm">
                    <p>ご予約確定時にデポジット（お一人様1,000円）のお支払いをお願いしております。</p>
                    <p>デポジットは当日のお会計から差し引かれます。</p>
                    <p>キャンセルポリシー: 7日前まで全額返金 / 3〜6日前 50%返金 / 2日前〜当日 返金なし / 無断キャンセル 返金なし</p>
                </div>

                {message ? <p className="reservation-message reservation-message--error">{message}</p> : null}
                <button className="reservation-submit-button" type="submit">問い合わせを送信する</button>
            </form>

            <div className="reservation-back" style={{ marginTop: "1rem" }}>
                <Link href="/" className="reservation-back-button">トップへ戻る</Link>
            </div>
        </main>
    );
}
