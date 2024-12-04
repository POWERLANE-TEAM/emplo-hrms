import addGlobalListener, { GlobalListener } from 'globalListener-script';

export default function initIframeFullScreener(id) {
    new GlobalListener('click', document, `[aria-controls="iframe-${id}"]`, (event) => {
        const resumeViewer = document.getElementById(`iframe-${id}`);
        const container = resumeViewer.parentElement;
        if (!document.fullscreenElement) {
            event.target.classList.remove('text-dark');
            event.target.classList.add('text-light');
            container.requestFullscreen();
        } else {
            event.target.classList.remove('text-light');
            event.target.classList.add('text-dark');
            document.exitFullscreen();
        }
    });
}
