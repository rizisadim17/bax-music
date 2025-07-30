import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["content", "toggleButton", "toggleText", "toggleIcon"]
    static values = { count: Number }

    connect() {
        this.isExpanded = false
    }

    toggle() {
        this.isExpanded = !this.isExpanded
        
        if (this.isExpanded) {
            this.contentTarget.classList.remove("hidden")
            this.toggleTextTarget.textContent = "Hide Episodes"
            this.toggleIconTarget.classList.add("rotate-180")
        } else {
            this.contentTarget.classList.add("hidden")
            this.toggleTextTarget.textContent = "Show Episodes"
            this.toggleIconTarget.classList.remove("rotate-180")
        }
    }
}