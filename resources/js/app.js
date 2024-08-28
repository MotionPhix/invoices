import "./bootstrap";

import 'maz-ui/styles';

import "../css/app.css";

import 'vfonts/Inter.css';

import "@protonemedia/laravel-splade/dist/style.css";

import {createApp} from "vue/dist/vue.esm-bundler.js";

import {renderSpladeApp, SpladePlugin} from "@protonemedia/laravel-splade";

import CustomInput from "@/components/CustomInput.vue";

import AddressFieldGroup from "@/components/AddressFieldGroup.vue";

import { UseDark } from "@vueuse/components";

const el = document.getElementById("app");

createApp({
  render: renderSpladeApp({el})
})
  .use(SpladePlugin, {
    "max_keep_alive": 10,
    "transform_anchors": false,
    "progress_bar": true,
    "components": {
      CustomInput,
      AddressFieldGroup,
      UseDark
    }
  })
  .mount(el);
