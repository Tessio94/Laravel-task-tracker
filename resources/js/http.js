import axios from "axios";

const http = axios.create({
    headers: {
        "X-Requested-With": "XMLHttpRequest",
    },
});

http.interceptors.response.use(
    (response) => response,
    (error) => {
        if (!error.response) {
            alert("Unable to connect to the server.");

            return Promise.reject(error);
        }

        const status = error.response.status;

        switch (status) {
            case 401:
                window.location.href = "/login";
                break;

            case 419:
                window.location.reload();
                break;

            case 403:
            case 404:
            case 500:
                alert(getErrorMessage(status));
                break;
        }

        return Promise.reject(error);
    },
);

function getErrorMessage(status) {
    const messages = {
        403: "You do not have permission to perform this action.",
        404: "The requested resource was not found.",
        500: "A server error occurred.",
    };

    return messages[status] || "An unexpected error occurred.";
}

export default http;
