import { Head, Link, router } from "@inertiajs/react";

export default function BanquetList({ inquiries, filters = {} }) {
    const submitFilter = (e) => {
        e.preventDefault();
        const fd = new FormData(e.currentTarget);
        router.get(route("admin.banquet.index"), {
            status: fd.get("status") || "",
            q: fd.get("q") || "",
        });
    };

    return (
        <div className="admin-reservation-page">
            <Head title="宴会管理" />
            <h1 className="admin-reservation-title">宴会管理</h1>

            <form onSubmit={submitFilter} style={{ display: "flex", gap: "8px", marginBottom: "12px" }}>
                <select name="status" defaultValue={filters.status || ""}>
                    <option value="">全て</option>
                    <option value="pending">問い合わせ受付</option>
                    <option value="confirmed_by_store">店舗確認済</option>
                    <option value="deposit_sent">請求送信済</option>
                    <option value="deposit_paid">入金済</option>
                    <option value="completed">完了</option>
                    <option value="canceled">キャンセル</option>
                </select>
                <input type="text" name="q" defaultValue={filters.q || ""} placeholder="氏名・メール・電話で検索" />
                <button type="submit">絞り込み</button>
            </form>

            <table style={{ width: "100%", borderCollapse: "collapse" }}>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>氏名</th>
                        <th>人数</th>
                        <th>希望日</th>
                        <th>ステータス</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    {(inquiries?.data || []).map((i) => (
                        <tr key={i.id}>
                            <td>{i.id}</td>
                            <td>{i.name}</td>
                            <td>{i.party_size}名</td>
                            <td>{i.preferred_date}</td>
                            <td>{i.status_label}</td>
                            <td>
                                <Link href={route("admin.banquet.show", i.id)}>詳細</Link>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}
