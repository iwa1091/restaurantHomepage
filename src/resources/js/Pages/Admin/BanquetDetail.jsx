import { Head, Link, router } from "@inertiajs/react";

export default function BanquetDetail({ inquiry }) {
    const updateStatus = (status) => {
        router.put(route("admin.banquet.update", inquiry.id), { status });
    };

    return (
        <div className="admin-reservation-page">
            <Head title="宴会詳細" />
            <div className="admin-reservation-back">
                <Link href={route("admin.banquet.index")} className="admin-reservation-back-link">宴会一覧へ戻る</Link>
            </div>

            <h1 className="admin-reservation-title">宴会詳細 #{inquiry.id}</h1>
            <p>氏名: {inquiry.name}</p>
            <p>メール: {inquiry.email}</p>
            <p>電話: {inquiry.phone}</p>
            <p>人数: {inquiry.party_size}名</p>
            <p>希望日: {inquiry.preferred_date}</p>
            <p>ステータス: {inquiry.status_label}</p>
            <p>デポジット: {inquiry.deposit_amount ? `¥${Number(inquiry.deposit_amount).toLocaleString()}` : "未設定"}</p>

            <div style={{ display: "flex", gap: "8px", flexWrap: "wrap", marginTop: "16px" }}>
                <button type="button" onClick={() => updateStatus("confirmed_by_store")}>店舗確認済にする</button>
                <button type="button" onClick={() => router.post(route("admin.banquet.send-deposit", inquiry.id))}>デポジット送信</button>
                <button type="button" onClick={() => router.post(route("admin.banquet.cancel", inquiry.id))}>キャンセル</button>
                <button type="button" onClick={() => router.post(route("admin.banquet.refund", inquiry.id))}>返金実行</button>
            </div>
        </div>
    );
}
