
import api from '../lib/api'; // ベースとなるAxiosインスタンスをインポート

const RESERVATION_BASE_URL = '/reservations'; // 一般ユーザー向け予約APIのベースURL

/**
 * 予約関連のAPIクライアント関数群
 * 主に空き時間チェックと予約作成に使用されます。
 */

/**
 * 指定された日付と人数に基づき、予約可能な空き時間枠を取得します。
 * * @param {string} date - 確認する日付 (形式: YYYY-MM-DD)
 * @param {number} partySize - 予約人数
 * @returns {Promise<Array<Object>>} 予約可能な時間スロットの配列 (例: [{ start: '10:00', end: '11:00' }, ...])
 */
export const checkAvailability = async (date, partySize) => {
    try {
        const response = await api.get(`${RESERVATION_BASE_URL}/check`, {
            params: {
                date: date,
                party_size: partySize,
            }
        });
        return response.data.available_slots;
    } catch (error) {
        console.error("空き時間の確認に失敗しました:", error);
        throw error;
    }
};

/**
 * 予約を新規作成します。
 * * @param {Object} reservationData - 予約データ (date, start_time, service_id, name, email, notesなど)
 * @returns {Promise<Object>} 作成された予約オブジェクト
 */
export const createReservation = async (reservationData) => {
    try {
        const response = await api.post(RESERVATION_BASE_URL, reservationData);
        // レスポンスには 'message' と 'reservation' データが含まれる想定
        return response.data;
    } catch (error) {
        console.error("予約の作成に失敗しました:", error.response?.data || error.message);
        throw error;
    }
};
