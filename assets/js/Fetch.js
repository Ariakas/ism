async function request(url, data = {}, method = "GET") {
    let response;
    if (method === "POST") {
        let form = new FormData();
        for (let key of Object.keys(data)) {
            form.append(key, data[key]);
        }
        response = await fetch(url, {
            method: method,
            body: form
        });
    }
    else {
        if (Object.keys(data).length) {
            let params = new URLSearchParams(data);
            response = await fetch(`${url}?${params.toString()}`, {
                method: method
            });
        }
        else {
            response = await fetch(`${url}`, {
                method: method
            });
        }
    }
    response = await response.json();
    if (response.status === "error") {
        console.error(response.detail);
    }
    return response;
}