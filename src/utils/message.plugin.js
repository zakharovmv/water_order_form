import langFilter from "@/filters/lang.filter";
import M from "materialize-css";

export default {
  install(Vue) {
    Vue.prototype.$message = function(html) {
      M.toast({ html, classes: "green" });
    };

    Vue.prototype.$error = function(html) {
      M.toast({
        html: `[${langFilter("Error")}]: ${html}`,
        classes: "red"
      });
    };
  }
};
