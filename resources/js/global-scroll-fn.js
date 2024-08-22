export default function addGlobalScrollListener(callback) {
    window.onscroll = callback;
}

export function documentScrollPosY() {
    const scrollPosition = Math.max(
        document.body.scrollTop,
        document.documentElement.scrollTop
    );
    return scrollPosition;
}


