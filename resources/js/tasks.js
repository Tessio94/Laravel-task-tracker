import http from "./http.js";
import { addGlobalEventListener, initDeleteHandlers } from "./utils.js";

export async function toggleTaskCompletion(taskId, button) {
    const url = `/tasks/${taskId}/toggle-completion`;

    button.disabled = true;

    try {
        const response = await http.patch(url);

        return response.data;
    } finally {
        button.disabled = false;
    }
}

export function initTaskCompletionHandlers() {
    addGlobalEventListener("click", "[data-task-toggle]", async (e, button) => {
        e.preventDefault();

        const taskId = button.dataset.taskId;
        const container = button.closest("[data-task-item]");

        const { completed } = await toggleTaskCompletion(taskId, button);

        if (container) {
            container.dataset.completed = completed;
        }
    });
}

export function initTaskDeleteHandlers() {
    initDeleteHandlers(
        "[data-task-delete]",
        (id) => `/tasks/${id}`,
        "[data-task-item]",
    );
}

export function initRecurringTaskDeleteHandlers() {
    initDeleteHandlers(
        "[data-recurring-task-delete]",
        (id) => `/recurring-tasks/${id}`,
        "[data-recurring-task-item]",
    );
}

export function initCategoryDeleteHandlers() {
    initDeleteHandlers(
        "[data-category-delete]",
        (id) => `/categories/${id}`,
        "[data-category-item]",
    );
}
