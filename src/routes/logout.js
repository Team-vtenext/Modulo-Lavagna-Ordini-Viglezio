import { backend } from "../config/config.backend";

let baseURL = backend.baseurl;

const headers = {
  "Content-Type": "application/json",
  Accept: "application/json",
};

export async function post(req, res) {
  try {
    const result = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/boardBackend.php?action=logout",
      {
        method: "POST",
        headers,
      }
    );

    const parsed = await result.json();

    req.session.token = "";
    res.end(JSON.stringify({ token: parsed.token }));
  } catch (error) {
    res.end(JSON.stringify({ error: error.message }));
  }
}
