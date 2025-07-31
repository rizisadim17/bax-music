import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['overlay', 'image', 'container', 'link'];

    connect() {
        this.detailsShown = false;

        if (this.hasContainerTarget) {
            this.containerTarget.addEventListener('click', this.handleClick.bind(this));
        }
    }

    disconnect() {
        if (this.hasContainerTarget) {
            this.containerTarget.removeEventListener('click', this.handleClick.bind(this));
        }
    }

    handleClick(event) {
        if (!this.detailsShown) {
            event.preventDefault();
            this.showDetails();
            this.detailsShown = true;

            setTimeout(() => {
                this.hideDetails();
                this.detailsShown = false;
            }, 1000);
        } else {
            this.hideDetails();
            this.detailsShown = false;
        }
    }

    showDetails() {
        if (this.hasOverlayTarget) {
            this.overlayTarget.classList.remove('opacity-0', 'translate-y-2');
            this.overlayTarget.classList.add('opacity-100', 'translate-y-0');
        }
        if (this.hasImageTarget) {
            this.imageTarget.classList.add('scale-110');
        }
    }

    hideDetails() {
        if (this.hasOverlayTarget) {
            this.overlayTarget.classList.remove('opacity-100', 'translate-y-0');
            this.overlayTarget.classList.add('opacity-0', 'translate-y-2');
        }
        if (this.hasImageTarget) {
            this.imageTarget.classList.remove('scale-110');
        }
    }
}
