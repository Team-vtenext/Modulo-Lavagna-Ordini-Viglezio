import { register, init, locale as $locale } from "svelte-i18n";
import { backend } from "./config/config.backend";
import { setCookie, getCookie } from "./config/cookie.js";

let baseURL = backend.baseurl;

const initialLanguage = async () => getLanguageFromDb();

const INIT_OPTIONS = {
  fallbackLocale: "en",
  initialLocale: "en",
  loadingDelay: 200,
  formats: {},
  warnOnMissingMessages: true,
};

let currentLocale = null;

register("it", () => import("./langs/it.json"));
register("en", () => import("./langs/en.json"));

async function getLanguageFromDb() {
  const res = await fetch(
    baseURL +
      "modules/SDK/src/modules/Webservices/boardBackend.php?action=getSettings",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
    }
  );
  let settings = await res.json();

  return settings.language;
}

$locale.subscribe((value) => {
  if (value == null) return;

  currentLocale = value;

  // if running in the client, save the language preference in a cookie
  if (typeof window !== "undefined") {
    setCookie("locale", value);
  }
});

// initialize the i18n library in client
export async function startClient() {

  init({
    ...INIT_OPTIONS,
    initialLocale: await initialLanguage(),
  });
}

// init only for routes (urls with no extensions such as .js, .css, etc) and for service worker
const DOCUMENT_REGEX = /(^([^.?#@]+)?([?#](.+)?)?|service-worker.*?\.html)$/;
// initialize the i18n library in the server and returns its middleware
export function i18nMiddleware() {
  // initialLocale will be set by the middleware
  init(INIT_OPTIONS);

  return (req, res, next) => {
    const isDocument = DOCUMENT_REGEX.test(req.originalUrl);
    // get the initial locale only for a document request
    if (!isDocument) {
      next();
      return;
    }

    let locale = getCookie("locale", req.headers.cookie);

    // no cookie, let's get the first accepted language
    if (locale == null) {
      if (req.headers["accept-language"]) {
        const headerLang = req.headers["accept-language"].split(",")[0].trim();
        if (headerLang.length > 1) {
          locale = headerLang;
        }
      } else {
        locale = INIT_OPTIONS.initialLocale || INIT_OPTIONS.fallbackLocale;
      }
    }

    if (locale != null && locale !== currentLocale) {
      $locale.set(locale);
    }

    next();
  };
}
