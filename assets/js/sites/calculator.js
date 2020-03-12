function addMissingBrackets(raw){
    var numberOfStartingBrackets = raw.split("(").length - 1;
    var numberOfClosingBrackets = raw.split(")").length - 1;
    if(numberOfStartingBrackets > numberOfClosingBrackets)
        raw = raw + ")".repeat(numberOfStartingBrackets - numberOfClosingBrackets);
    else if(numberOfStartingBrackets < numberOfClosingBrackets)
        raw = "(".repeat(numberOfClosingBrackets - numberOfStartingBrackets) + raw;
    return raw;
}

function evalMathString(raw, lastResult = "0") {
    raw = raw
        .split("÷").join("/")
        .split("×").join("*")
        .split("∞").join("Infinity")
        .split("ANS").join(lastResult)
        .split("rand(").join("rand(")
        .split("RAND").join("rand()")
        .split("sqrt(").join("Math.sqrt(")
        .split("abs(").join("Math.abs(")
        .split("sin(").join("Math.sin(")
        .split("cos(").join("Math.cos(")
        .split("tan(").join("Math.tan(")
        .split("sinh(").join("Math.sinh(")
        .split("cosh(").join("Math.cosh(")
        .split("tanh(").join("Math.tanh(")
        .split("asin(").join("Math.asin(")
        .split("acos(").join("Math.acos(")
        .split("atan(").join("Math.atan(")
        .split("asinh(").join("Math.asinh(")
        .split("acosh(").join("Math.acosh(")
        .split("atanh(").join("Math.atanh(")
        .split("ln(").join("Math.log(")
        .split("lg(").join("Math.log10(")
        .split("log(").join("log(")
        .split("pow(").join("Math.pow(")
        .split("exp(").join("Math.exp(")
        .split("floor(").join("Math.floor(")
        .split("ceil(").join("Math.ceil(")
        .split("round(").join("Math.round(")
        .split("trunc(").join("Math.trunc(")
        .split("cbrt(").join("Math.cbrt(")
        .split("fac(").join("fac(")
        .split("π").join("Math.PI")
        .split("ℯ").join("Math.E")
        .split(";").join(",");

    console.info("Prepaired statement: " + raw);

    //Functions used by eval()

    function fac(x){
        return x <= 2 ? 2 : x * fac(x - 1);
    }

    function log(base, x){
        return Math.log(x) / Math.log(base);
    }

    function rand(min, max) {
        if(min != undefined && max != undefined)
            return Math.floor(Math.random() * (+max - +min)) + +min;
        else if(min != undefined)
            return Math.floor(Math.random() * min)
        else
            return Math.random();
    }

    var res = "";
    try {
        res = '' + eval(raw);
    } catch (error) {
        res = '<span class="error">' + error.message + '</span>';
    }

    res = res
        .split("Infinity").join("∞");

    return res;
}

function initCalculatorButtons() {
    const $output = $("#calculatorOutput");
    const $calculation = $(".calculation");
    const $buttons = $(".calculatorButton");
    const $equalButton = $(".equalButton");
    const $acButton = $(".acButton");
    const $cButton = $(".cButton");
    const $lastButton = $(".lastButton");
    const $invertButton = $(".invertButton");
    const $history = $(".historyentrys");

    var lastRaw = "";
    var lastResult = "";

    function isInverted() {
        return $invertButton.data("inverted") == "true";
    }

    $invertButton.click(function(){
        const useNormal = isInverted();
        $buttons.each(function(){
            const $e = $(this);
            if(useNormal){
                const data = $e.data("normal");
                if(data)
                    $e.html(data);
            }else {
                const data = $e.data("inverted");
                if(data)
                    $e.html(data);
            }
        });
        if(useNormal){
            $invertButton.data("inverted", "false");
            $invertButton.css("background", "lightcoral");
        }else {
            $invertButton.data("inverted", "true");
            $invertButton.css("background", "coral");
        }
    });

    $buttons.click(function(){
        const $this = $(this);
        var add = $this.data("normal-value");
        
        if(isInverted()){
            const invertedAdd = $this.data("inverted-value");
            if(invertedAdd)
                add = invertedAdd;
        }

        $output.html($output.html() + add);
    });

    $lastButton.click(function(){
        $output.html(lastRaw);
    });

    $acButton.click(function(){
        $output.html("");
    });

    $cButton.click(function(){
        const str = $output.html();
        $output.html(str.substring(0, str.length - 1));
    });

    function calculate(){
        lastRaw = addMissingBrackets($output.text());
        $output.text("...");

        lastResult = evalMathString(lastRaw, lastResult);

        $history.prepend(`
            <div class="entry">
                <span class="raw inputsetter">${lastRaw}</span>
                <span class="equal">=</span>
                <span class="result inputsetter">${lastResult}</span>
            </div>
        `).find(".inputsetter").unbind('click').click(function(){
            $output.append($(this).html());
        });

        $calculation.html(lastRaw + " = " + lastResult);
        $output.html(lastResult);
    }

    $(document).on('keypress',function(e) {
        if((e.which== 13) && ($(e.target)[0]!=$("textarea")[0])){
            e.preventDefault();
            e.stopPropagation();
            calculate();
        }
    });

    $equalButton.click(calculate);
}

export function initCalculator(){
    initCalculatorButtons();
}