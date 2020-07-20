import config from "@/system/config";
import ru from "@/lang/ru.json";

const locales = {
  "ru-RU": ru
}

export default function langFilter(key) {
  const lang = config.lang || "ru-RU";
  return locales[lang][key] || `[Localize error]: key ${key} not found`;
}
