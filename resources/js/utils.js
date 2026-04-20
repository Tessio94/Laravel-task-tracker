import http from "./http.js";

export function addGlobalEventListener(
    type,
    selector,
    callback,
    parent = document,
) {
    parent.addEventListener(type, (e) => {
        const target = e.target.closest(selector);

        if (target) {
            callback(e, target);
        }
    });
}

export async function deleteResource(url, button) {
    button.disabled = true;

    try {
        await http.delete(url);

        return true;
    } finally {
        button.disabled = false;
    }

    return false;
}

export function initDeleteHandlers(selector, urlBuilder, rowSelector) {
    addGlobalEventListener("click", selector, async (e, button) => {
        e.preventDefault();

        if (!confirm("Are you sure you want to delete this item?")) {
            return;
        }

        const id = button.dataset.id;
        const url = urlBuilder(id);

        const deleted = await deleteResource(url, button);

        if (deleted) {
            const row = button.closest(rowSelector);

            row?.remove();
        }
    });
}
