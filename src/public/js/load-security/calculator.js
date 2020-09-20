/* 
 * Author: Junior Rimac 
 * 
 */

function getCalculatorResult(values) {
    var antall = values.noOfTyings;
    var lc = values.loadCapacity;
    var hvinkel = values.horizontalAngle;
    var vvinkel = values.verticalAngle;

    var finalResult = (antall * lc * (Math.cos(hvinkel * Math.PI / 180)) * (Math.cos(vvinkel * Math.PI / 180)));
    return finalResult;
}

function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals), sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function rotateArrow(objName, imgUrl, angle, position) {

    var img = document.createElement('IMG');
    img.id = objName;
    img.name = objName;
    img.src = imgUrl;

    var old = document.getElementById(objName);
    old.parentNode.replaceChild(img, old);

    var r = objName === 'horizontalAngleLine' ? 33 : 18;
    rotate(document.getElementById(objName), angle, r, position);
}

function rotate(obj, angulo, radius, position) {
    if (angulo >= 0) {
        var rotation = Math.PI * angulo / 180;
    } else {
        var rotation = Math.PI * (360 + angulo) / 180;
    }
    var costheta = Math.cos(rotation);
    var sintheta = Math.sin(rotation);

    if (document.createElement("canvas").getContext) {
        var c = document.createElement('canvas');
        c.width = Math.abs(costheta * obj.width) + Math.abs(sintheta * obj.height);
        c.style.width = c.width + 'px';
        c.height = Math.abs(costheta * obj.height) + Math.abs(sintheta * obj.width);
        c.style.height = c.height + 'px';
        c.id = obj.id;
        c.style.position = 'absolute';
        c.style.top = position.top;
        c.style.left = position.left; //95;
        c.style.marginTop = (Math.abs(obj.height - c.height) / 2 * -1) + "px";
        c.style.marginLeft = (Math.abs(obj.width - c.width) / 2 * -1) + "px";

        var ctx = c.getContext('2d');
        ctx.save();
        if (rotation <= Math.PI / 2) {
            ctx.translate(sintheta * obj.height, 0);
        } else if (rotation <= Math.PI) {
            ctx.translate(c.width, -costheta * obj.height);
        } else if (rotation <= 1.5 * Math.PI) {
            ctx.translate(-costheta * obj.width, c.height);
        } else {
            ctx.translate(0, -sintheta * obj.width);
        }
        ctx.rotate(rotation);
        ctx.drawImage(obj, 0, 0, obj.width, obj.height);
        obj.parentNode.replaceChild(c, obj);
        ctx.restore();
    } else {
        /* ---- DXImageTransform ---- */
        obj.style.position = 'absolute';
        obj.style.marginTop = -radius * Math.abs(Math.sin(2 * rotation));
        obj.style.marginLeft = -radius * Math.abs(Math.sin(2 * rotation));
        obj.style.filter = "progid:DXImageTransform.Microsoft.Matrix(sizingMethod='auto expand')";
        obj.filters.item(0).M11 = costheta;
        obj.filters.item(0).M12 = -sintheta;
        obj.filters.item(0).M21 = sintheta;
        obj.filters.item(0).M22 = costheta;
    }
}
