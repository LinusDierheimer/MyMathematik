import "./app.js"
import { color } from "d3";
window.d3 = require("d3");
const plotter = require("function-plot");
const deepClone = require("clone-deep");

let plotterOptions;
let idCounter = 0;

const DEFAULT_COLORS = [
    "#4169E1", // royale blue
    "#3CB371", // medium see green
    "#800080", // purple
    "#8B0000", // dark red
    "#FFD700", // gold
    "#E9967A", // dark salmon
    "#D2691E", // chocolate
    "#B22222", // firebrick
    "#FFA500", // orange
    "#9ACD32", // yellowgren
];

function getColor(id) {
    const index = id % DEFAULT_COLORS.length;
    return DEFAULT_COLORS[index];
}

function plot() {
    console.log("plotting: ", plotterOptions);
    $("#plotter_result").children().remove();
    plotter(deepClone(plotterOptions));
}

function loadTemplate(template, container, id) {
    const $template = $(template);
    const $container = $(container);

    $container.append(
        $template.html().split("§").join(id) 
    );
}

function getIndexOfId(array, id) {
    for (let i = 0; i < array.length; i++) {
        if(array[i].id === id)
            return i;
    }
    return -1;
}

function initCommon(array, f, id) {

    f.id = id;

    f.color = getColor(id);
    const $color = $(`#function_color_${id}`);
    $color.val(f.color);
    $color.change(function() {
        f.color = $color.val();
        console.log(f.color);
        plot();
    });

    f.closed = false;
    const $closed = $(`#function_closed_${id}`);
    $closed.change(function() {
        f.closed = $closed.is(":checked");
        plot();
    });

    f.range = [-Infinity, Infinity];

    const $from = $(`#function_from_${id}`);
    $from.change(function() {
        const val = $from.val();
        if(val === "")
            f.range[0] = -Infinity;
        else
            f.range[0] = Number(val);
        plot(); 
    });

    const $to = $(`#function_to_${id}`);
    $to.change(function() {
        const val = $to.val();
        if(val === "")
            f.range[1] = Infinity;
        else
            f.range[1] = Number(val);
        plot(); 
    });

    const $delete = $(`#function_delete_${id}`);
    $delete.click(function() {
        $(`#function_${id}`).remove();
        array.splice(getIndexOfId(array, id), 1);

        if(plotterOptions.data.length == 0) {
            idCounter = 0;
            addNormalFunction();
        } else { //dont plot two times
            plot();
        }
    });

}

function initTopLevel(f, id) {
    let array = plotterOptions.data;
    initCommon(array, f, id);    

    const $tip = $(`#function_tip_${id}`);
    $tip.change(function() {
        const val = $tip.val();
        if(val === "point") {
            plotterOptions.tip.xLine = false;
            plotterOptions.tip.yLine = false;
            f.skipTip = false;
        } else if(val === "line") {
            plotterOptions.tip.xLine = true;
            plotterOptions.tip.yLine = true;
            f.skipTip = false;
        } else {
            f.skipTip = true;
        }
        plot();
    });

}

function addSecantFunction(pf) {
    const id = idCounter++;
    let f = {};
    pf.secants.push(f);

    loadTemplate("#function_template_secant", `#function_container_${pf.id}`, id);
    initCommon(pf.secants, f, id);

    f.x0 = 1;
    const $x0input = $(`#function_x0input_${id}`);
    $x0input.val(f.x0);
    $x0input.change(function() {
        f.x0 = Number($x0input.val());
        plot();
    })

    f.x1 = 2;

    const $x1input = $(`#function_x1input_${id}`);
    $x1input.val(f.x1);
    $x1input.change(function() {
        f.x1 = Number($x1input.val());
        plot();
    });

    f.updateOnMouseMove = false;
    const $x1mouse = $(`#function_mouse_${id}`);

    $(`#function_set_input_${id}`).click(function() {
        $x1input.css("display", "block");
        $x1mouse.css("display", "none");
        f.updateOnMouseMove = false;
        plot();
    });
    $(`#function_set_mouse_${id}`).click(function() {
        $x1input.css("display", "none");
        $x1mouse.css("display", "block");
        f.updateOnMouseMove = true;
        plot();
    });

    plot();
}

function addNormalFunction() {
    const id = idCounter++;
    let f = {};
    plotterOptions.data.push(f);

    loadTemplate("#function_template_normal", "#function_container", id);
    initTopLevel(f, id);

    const index = plotterOptions.data.length - 1;
    f.fn = `x + ${index}`;
    const $input = $(`#function_input_${id}`);
    $input.val(index === 0 ? "x" : `x + ${index}`)
    $input.change(function() {
        f.fn = $input.val();
        plot();
    });

    f.secants = [];
    const $secant = $(`#function_secant_${id}`);
    $secant.click(function() {
        addSecantFunction(f);
    });

    plot();

}

function initPlotter() {

    const $container = $("#plotter_result");

    plotterOptions = {
        target: "#plotter_result",
        width: $container.width(),
        height: $container.height(),
        grid: true,
        tip: {
            xLine: false,
            yLine: false
        },
        data: []
    };

    $container.resize(function () {
        plotterOptions.width = $container.width();
        plotterOptions.height = $container.height();
        plot();
    });

    const $addNormal = $("#function_add_normal");
    $addNormal.click(function() {
        addNormalFunction();
    });

    addNormalFunction();

}

$(document).ready(function () {
    initPlotter();
})