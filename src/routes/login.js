import { backend } from "../config/config.backend";

let baseURL = backend.baseurl;

const headers = {
  "Content-Type": "application/json",
  Accept: "application/json",
};

export async function post(req, res) {
  try {
    const { value } = req.body;

    const result = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/boardBackend.php?action=login",
      {
        method: "POST",
        headers,
        body: JSON.stringify({ value }),
      }
    );

    const parsed = await result.json();
    if (typeof parsed.error !== "undefined") {
      throw new Error(parsed.error);
    }

    req.session.token = parsed.token;
    res.end(JSON.stringify({ token: parsed.token }));
  } catch (error) {
    res.end(JSON.stringify({ error: error.message }));
  }
}
