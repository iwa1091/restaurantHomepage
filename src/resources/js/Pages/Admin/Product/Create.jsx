// /resources/js/Pages/Admin/Product/Create.jsx
import React, { useState } from "react";
import { useForm, router } from "@inertiajs/react";
import { motion } from "framer-motion";
import { route } from "ziggy-js";

// CSS モジュール
import "../../../../css/pages/admin/product/create.css";

export default function Create() {
    const MAX_IMAGE_SIZE = 500 * 1024;
    const ALLOWED_IMAGE_TYPES = [
        "image/jpeg",
        "image/png",
        "image/gif",
        "image/webp",
    ];
    const { data, setData, post, processing, errors, reset } = useForm({
        name: "",
        price: "",
        description: "",
        image: null,
        stock: 0,
    });

    const [preview, setPreview] = useState(null);
    const [imageError, setImageError] = useState(null);

    const handleFileChange = (e) => {
        const file = e.target.files?.[0] ?? null;
        if (!file) {
            setData("image", null);
            setPreview(null);
            setImageError(null);
            return;
        }

        if (!ALLOWED_IMAGE_TYPES.includes(file.type)) {
            setData("image", null);
            setPreview(null);
            setImageError(
                "画像はjpeg/png/gif/webp形式のみアップロードできます。"
            );
            e.target.value = "";
            return;
        }

        if (file.size > MAX_IMAGE_SIZE) {
            setData("image", null);
            setPreview(null);
            setImageError(
                "画像は500KB以内のファイルをアップロードしてください。"
            );
            e.target.value = "";
            return;
        }

        setImageError(null);
        setData("image", file);
        if (file) {
            setPreview(URL.createObjectURL(file));
        } else {
            setPreview(null);
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        const csrfToken =
            document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content") || "";

        post(route("admin.products.store"), {
            forceFormData: true, // ✅ ファイル送信を確実にする
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            onSuccess: () => {
                reset();
                setPreview(null);
            },
        });
    };

    return (
        <motion.div
            className="admin-product-create-page"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
        >
            <div className="admin-product-create-container">
                <h1 className="admin-product-create-title">商品登録</h1>

                <form
                    onSubmit={handleSubmit}
                    className="admin-product-create-form"
                    encType="multipart/form-data"
                    noValidate
                >
                    {/* 商品名 */}
                    <div className="admin-product-create-field">
                        <label className="admin-product-create-label">
                            商品名
                        </label>
                        <input
                            type="text"
                            name="name"
                            value={data.name}
                            onChange={(e) => setData("name", e.target.value)}
                            className="admin-product-create-input"
                            placeholder="商品名を入力"
                        />
                        {errors.name && (
                            <p className="admin-product-create-error">
                                {errors.name}
                            </p>
                        )}
                    </div>

                    {/* 価格 */}
                    <div className="admin-product-create-field">
                        <label className="admin-product-create-label">
                            価格
                        </label>
                        <input
                            type="number"
                            name="price"
                            value={data.price}
                            onChange={(e) => setData("price", e.target.value)}
                            className="admin-product-create-input"
                            placeholder="価格を入力"
                        />
                        {errors.price && (
                            <p className="admin-product-create-error">
                                {errors.price}
                            </p>
                        )}
                    </div>

                    {/* 商品説明 */}
                    <div className="admin-product-create-field">
                        <label className="admin-product-create-label">
                            商品説明
                        </label>
                        <textarea
                            name="description"
                            value={data.description}
                            onChange={(e) =>
                                setData("description", e.target.value)
                            }
                            className="admin-product-create-textarea"
                            placeholder="商品の説明を入力"
                            rows={5}
                        />
                        {errors.description && (
                            <p className="admin-product-create-error">
                                {errors.description}
                            </p>
                        )}
                    </div>

                    {/* 商品画像 */}
                    <div className="admin-product-create-field">
                        <label className="admin-product-create-label">
                            商品画像
                        </label>
                        <input
                            type="file"
                            onChange={handleFileChange}
                            className="admin-product-create-file"
                            accept="image/jpeg,image/png,image/gif,image/webp"
                        />
                        <p className="admin-product-create-hint">
                            jpeg・png・gif・webp形式、500KB以内のファイルを選択してください。
                        </p>
                        {preview && (
                            <div className="admin-product-create-preview-wrapper">
                                <div className="admin-product-create-preview-inner">
                                    <img
                                        src={preview}
                                        alt="preview"
                                        className="admin-product-create-preview-image"
                                    />
                                </div>
                            </div>
                        )}
                        {(imageError || errors.image) && (
                            <p className="admin-product-create-error">
                                {imageError ||
                                    "画像はjpeg/png/gif/webp形式、500KB以内でアップロードしてください。"}
                            </p>
                        )}
                    </div>

                    {/* 在庫数 */}
                    <div className="admin-product-create-field">
                        <label
                            htmlFor="stock"
                            className="admin-product-create-label"
                        >
                            在庫数
                        </label>
                        <input
                            type="number"
                            name="stock"
                            id="stock"
                            value={data.stock}
                            onChange={(e) => setData("stock", e.target.value)}
                            className="admin-product-create-input"
                            min="0"
                        />
                        {errors.stock && (
                            <p className="admin-product-create-error">
                                {errors.stock}
                            </p>
                        )}
                    </div>

                    {/* ボタンエリア */}
                    <div className="admin-product-create-actions">
                        <button
                            type="button"
                            onClick={() =>
                                router.visit(route("admin.products.index"))
                            }
                            className="admin-product-create-button admin-product-create-button--back"
                        >
                            戻る
                        </button>

                        <button
                            type="submit"
                            disabled={processing}
                            className="admin-product-create-button admin-product-create-button--submit"
                        >
                            {processing ? "登録中..." : "登録"}
                        </button>
                    </div>
                </form>
            </div>
        </motion.div>
    );
}
