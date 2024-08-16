// window.onscroll = function () {
//     if (document.body.scrollTop > 150 || document.documentElement.scrollTop > 150) {
//         document.querySelector(".element").classList.add("your_class_name");
//     } else {
//         document.querySelector(".element").classList.remove("your_class_name");
//     }
// };

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


