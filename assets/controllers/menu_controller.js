import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["mobileMenu", "mobileBtn"]

    toggleMobile() {
        this.mobileMenuTarget.classList.toggle("hidden")
    }

    closeMobile() {
        this.mobileMenuTarget.classList.add("hidden")
    }

    setActive(event) {
        const section = event.params.section
        console.log(`Navigating to ${section}`)
    }
}