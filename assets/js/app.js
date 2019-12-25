import "../scss/app.scss";

import "jquery";
import "bootstrap";

import { initCommon } from "./sites/common.js";
import { initHome } from "./sites/home.js";
import { initLogin } from "./sites/login.js";
import { initRegister } from "./sites/register.js";
import { initAccount } from "./sites/account.js";
import { initPost } from "./sites/post.js";
import { initCalculator } from "./sites/calculator.js";


$(document).ready(function(){
    initCommon();
    initHome();
    initLogin();
    initRegister();
    initAccount();
    initPost();
    initCalculator();
});