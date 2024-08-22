import GLOBAL_CONST from '../global-constant.js';

export default function initLucideIcons() {
    try {
        lucide.createIcons();
    } catch (error) {
        console.error(GLOBAL_CONST.ERR_ASSET_404);
    }
}
