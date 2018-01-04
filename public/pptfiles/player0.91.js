var aj = {
    Opacity: function(O, Value) {
        O.style.filter = 'alpha(opacity=' + (Math.floor(Value * 100)) + ')';
        O.style.opacity = Value;
    },
    Rotate: function(O, Value) {
        O.style[hx] = O.style[hx].replace(/rotate\([^)]*\)/g, "") + "rotate(%1deg)".replace(/%1/, Value);
    },
    iX: function(O) {
        var reg = /(rotate\([\-\+]?(\d+))deg\)/i;
        var jc = O.style[hx],
        nq = jc.match(reg);
        var nx = 0;
        if (nq && RegExp.$2) {
            nx = parseFloat(RegExp.$2);
        }
        return nx;
    },
    Scale: function(O, X, Y) {
        O.style[hx] = O.style[hx].replace(/scale\([^)]*\)/g, "") + "scale(%1,%2)".replace(/%1/, X).replace(/%2/, Y);
    },
    hX: function(O, X) {
        O.style[hx] = O.style[hx].replace(/scaleX\([^)]*\)/g, "") + "scaleX(%1)".replace(/%1/, X);
    },
    fc: function(O, Y) {
        O.style[hx] = O.style[hx].replace(/scaleY\([^)]*\)/g, "") + "scaleY(%1)".replace(/%1/, Y);
    },
    Move: function(O, X, Y) {
        O.style.left = X + "px";
        O.style.top = Y + "px";
    }
}
function dP(aN) {
    var aa = aN.getElementsByTagName("span");
    var tc = 0xffffff;
    var ah = aN;
    if (aa.length > 0) {
        ah = aa[0];
    } else if (aN.tagName.toLowerCase() == "span") {
        ah = aN;
    } else {
        return 0xffffff;
    }
    var bu = window.getComputedStyle(ah, null);
    var tc = bu.color;
    var df = /(.*?)rgb\((\d+), (\d+), (\d+)\)/.exec(tc);
    var red = parseInt(df[2]);
    var green = parseInt(df[3]);
    var blue = parseInt(df[4]);
    tc = blue | (green << 8) | (red << 16);
    return tc;
}
function cv(aN, p) {
    var first = aN.firstChild;
    if (first) {
        first = first.firstChild;
    }
    if (first == null) {
        return 0xffffff;
    }
    if (first.tagName.toLowerCase() == "svg") {
        var bA = first.firstChild.attributes["fill"].nodeValue;
        if (bA[0] == '#') {
            p.iU = first.firstChild;
            return parseInt(bA.substr(1), 16);
        }
    }
    return 0xffffff;
}
function cV(p, aQ) {
    if (p.iU) {
        p.iU.setAttribute("fill", "#" + aQ.toString(16));
    }
}
function jF(aN, p) {
    var first = aN.firstChild;
    if (first) {
        first = first.firstChild;
    }
    if (first.tagName.toLowerCase() == "svg") {
        var s = new XMLSerializer();
        var bA = s.serializeToString(aN);
        var pos = bA.indexOf("stroke=");
        p.g = first;
        if (pos >= 0) {
            var jn = bA.indexOf("'", 9);
            var im = bA.substring(pos + 8, jn);
            return parseInt(im, 16);
        }
    }
    return cv(aN, p);
}
function iO(p, aQ) {
    if (p.g) {
        var path = p.g.getElementsByTagName("path");
        for (var i = 0; i < path.length; i++) {
            path[i].setAttribute("stroke", "#" + aQ.toString(16));
        }
    }
}
function ad(aN, am, value) {
    var aa = aN.getElementsByTagName("SPAN");
    if (aa.length == 0) {
        if (aN.tagName.toLowerCase() == "span") {
            aN.style[am] = value;
        }
        return;
    }
    for (var i = 0; i < aa.length; i++) {
        aa[i].style[am] = value;
    }
}
function dF(src, hs, r) {
    var aQ = 0;
    for (var i = 0; i < 3; i++) {
        var bG = (src >> (8 * i)) & 0xff;
        var iy = (hs >> (8 * i)) & 0xff;
        bG = Math.floor(bG + (iy - bG) * r);
        aQ |= bG << (8 * i);
    }
    return aQ;
}
function ib(aN, p) {
    p.sx = p.dx = parseFloat(aN.style.left);
    p.sy = p.eff == 42 ? parseFloat(aN.style.top) - 50 : parseFloat(aN.style.top) + 50;
    p.dy = parseFloat(aN.style.top);
    p.ac = 0;
    p.bi = 1;
}
function bS(aN, p, r) {
    if (p.dx !== undefined) {
        aN.style.left = ((p.dx - p.sx) * r + p.sx) + "px";
        aN.style.top = ((p.dy - p.sy) * r + p.sy) + "px";
    }
    if (p.ac !== undefined) {
        aN.style.filter = 'alpha(opacity=' + (Math.floor(r * 100)) + ')';
        aN.style.opacity = r;
    }
    if (p.bv !== undefined && p.bU !== undefined) {
        aj.Scale(aN, p.bv + (p.aK - p.bv) * r, p.bU + (p.bk - p.bU) * r);
    } else {
        if (p.bv !== undefined) {
            aj.hX(aN, p.bv + (p.aK - p.bv) * r);
        } else if (p.bU !== undefined) {
            aj.fc(aN, p.bU + (p.bk - p.bU) * r);
        }
    }
    if (p.aO !== undefined) {
        aj.Rotate(aN, p.aO + (p.dr - p.aO) * r);
    }
}
function iI(aN, p) {
    if (typeof(p.sx) == undefined) {
        p.sx = parseFloat(aN.style.left) + 700;
        p.dx = parseFloat(aN.style.left);
        p.sy = parseFloat(aN.style.top) + 100;
        p.dy = parseFloat(aN.style.top);
    }
    p.bv = 1;
    p.aK = 0.3;
    p.aO = 45;
    p.dr = 0;
    aN.style[hx] = "rotate(" + p.aO + "deg) scale(1,1)";
}
function iE(aN, p, r) {
    if (r <= 0.6) {
        aj.Opacity(aN, r / 0.6);
        aj.Move(aN, ((p.dx - p.sx) * r / 0.6 + p.sx), ((p.sy - p.dy) * r / 0.6 + p.dy));
        var ba = (p.aO + (p.dr - p.aO) * r / 0.6);
        var gE = (p.bv + (p.aK - p.bv) * r / 0.6);
        aj.Rotate(aN, ba);
        aj.Scale(aN, gE, 1);
    } else {
        var gE = p.aK + (p.bv - p.aK) * (r - 0.6) / (1 - 0.6);
        aN.style.top = (p.sy + (p.dy - p.sy) * (r - 0.6) / (1 - 0.6)) + "px";
        aN.style[hx] = "rotate(0deg) scaleX(" + gE + ")";
    }
}
function jR(aN, p) {
    if (p.exit) {
        p.dy = parseFloat(aN.style.top) + 200;
        p.sy = parseFloat(aN.style.top);
        p.dx = parseFloat(aN.style.left) + 150;
        p.sx = parseFloat(aN.style.left);
    } else {
        p.dy = parseFloat(aN.style.top);
        p.sy = parseFloat(aN.style.top) - 200;
        p.dx = parseFloat(aN.style.left);
        p.sx = parseFloat(aN.style.left) - 150;
    }
    p.bU = 0.3;
    aN.style[hx] = "scaleY(0.3)";
}
function lg(aN, p, r) {
    r = p.exit ? 1 - r: r;
    var ratio = ip(r * p.duration, 0, 1, p.duration);
    var aq = p.sx + (p.dx - p.sx) * r;
    var by = p.sy + (p.dy - p.sy) * ratio;
    var hK = 0.3 + (1 - 0.3) * ratio;
    aj.Move(aN, aq, by);
    aj.fc(aN, hK);
}
function ip(F, b, c, d) {
    if ((F /= d) < (1 / 2.75)) {
        return c * (7.5625 * F * F) + b;
    } else if (F < (2 / 2.75)) {
        return c * (7.5625 * (F -= (1.5 / 2.75)) * F + .75) + b;
    } else if (F < (2.5 / 2.75)) {
        return c * (7.5625 * (F -= (2.25 / 2.75)) * F + .9375) + b;
    } else {
        return c * (7.5625 * (F -= (2.625 / 2.75)) * F + .984375) + b;
    }
}
function jS(aN, p) {
    p.sx = parseFloat(aN.style.left);
    p.sy = parseFloat(aN.style.top) + 200;
    p.ac = 0;
    p.dy = parseFloat(aN.style.top);
    p.bi = 1;
    p.iQ = 100 / Math.pow((p.sy - (p.sy + p.dy) / 2), 2);
    aj.Move(aN, p.sx, p.sy);
}
function jC(p, cy) {
    return - p.iQ * Math.pow((cy - (p.sy + p.dy) / 2), 2) + 100;
}
function kL(aN, p, r) {
    if (r <= 0.2) {
        aj.Opacity(aN, r / 0.2);
    } else {
        var cy = p.sy + (p.dy - p.sy) * (r - 0.2) / 0.8;
        aj.Move(aN, jC(p, cy) + p.sx, cy);
    }
}
function mp(aN, p) {}
function mq(aN, p, r) {
    if (r <= 0.1) {} else if (r < 0.5) {
        aN.style["font-color"] = p.mB;
    } else if (r < 0.9) {
        aN.style["font-color"] = p.mc;
    } else {
        aN.style["font-color"] = p.lT;
    }
}
function jP(aN, p) {
    p.bv = 2;
    p.ac = 0;;
    p.aK = 1;
    p.bi = 1;
    p.dx = p.sx = parseFloat(aN.style.left);
    p.dy = p.sy = parseFloat(aN.style.top);
    p.bU = p.bk = 1;
}
function lb(aN, p) {
    if (p.exit) aN.style[hx] = "scale(1,1)";
}
function kf(aN, p) {
    p.dx = parseFloat(aN.style.left);
    p.dy = parseFloat(aN.style.top);
    switch (p.dir) {
    case 3:
        p.sx = p.dx;
        p.sy = aP;
        break;
    case 4:
        p.sx = -aS - aN.scrollWidth;
        p.sy = p.dy;
        break;
    case 1:
        p.sx = p.dx;
        p.sy = -aP - aN.scrollHeight;
        break;
    case 2:
        p.sx = aS;
        p.sy = p.dy;
        break;
    }
}
function kH(aN, p) {
    p.dx = p.sx = parseFloat(aN.style.left);
    p.sy = aP;
    p.dy = -aN.scrollHeight;
}
function kS(aN, p) {
    p.sx = parseFloat(aN.style.left);
    p.sy = parseFloat(aN.style.top) + 200;
    p.ac = 0;
    p.dy = parseFloat(aN.style.top);
    p.bi = 1;
    p.iQ = 100 / Math.pow((p.dy - (p.sy + p.dy) / 2), 2);
    p.bv = p.bU = 1.3;
    p.aK = p.bk = 1;
    aN.style.top = p.sy + "px";
    aN.style[hx] = "scale(1.3,1.3)";
}
function lk(aN, p, r) {
    aj.Opacity(aN, r);
    var fE = p.sy + (p.dy - p.sy) * (r - 0.2) / 0.8;
    aj.Move(aN, jC(p, fE) + p.sx, fE);
    aj.Scale(aN, p.bv + (p.aK - p.bv) * r, p.bU + (p.bk - p.bU) * r);
}
function hV(aN, p) {
    if (p.exit) aN.style[hx] = "scale(1,1)";
}
function iM(aN, p) {
    p.dx = p.sx = parseFloat(aN.style.left);
    p.dy = p.sy = parseFloat(aN.style.top);
    p.bv = 0.65;
    p.aK = 1;
    p.ac = 0;
    p.bi = 1;
    aN.style[hx] = "scaleX(1)";
}
function jx(aN, p) {
    if (p.exit) aN.style[hx] = "scaleX(1)";
}
function ji(aN, p) {
    p.ac = 0;
    p.bi = 1;
}
function ju(aN, p) {
    p.ac = 0;
    aN.style[hx] = "scale(0,0)";
    p.bv = p.bU = 0;
    p.aK = p.bk = 1;
}
function je(aN, p) {
    if (p.exit) aN.style[hx] = "scale(1,1)";
}
function iL(aN, p) {
    aN.style.visibility = "hidden";
}
function jo(aN, p, r) {
    if (r < 0.1 || r > 0.9) {
        aN.style.visibility = "hidden";
    } else {
        aN.style.visibility = "";
    }
}
function lc(aN, p) {
    if (p.exit) {
        p.sy = aP;
        p.dy = parseFloat(aN.style.top);
        p.ha = 180;
        p.gD = 0;
        p.gP = 180;
        p.gV = 0;
    } else {
        p.sy = -aP;
        p.dy = parseFloat(aN.style.top);
        p.ha = -180;
        p.gD = 0;
        p.gP = -180;
        p.gV = 0;
    }
}
function jf(aN, p, r) {
    aN.style.top = (p.sy + (p.dy - p.sy) * r) + "px";
    var hj = p.ha + (p.gD - p.ha) * r;
    var ry = p.gP + (p.gV - p.gP) * r;
    aN.style[hx] = "rotateZ(" + hj + "deg) rotateY(" + ry + "deg)";
}
function jh(aN, p) {
    if (p.exit) aN.style[hx] = "rotateZ(0deg) rotateY(0deg)";
}
function jZ(aN, p) {
    if (p.aO && p.aO == -45) return;
    p.sx = parseFloat(aN.style.left) + 100;
    p.sy = parseFloat(aN.style.top) - 300;
    p.dx = parseFloat(aN.style.left);
    p.dy = parseFloat(aN.style.top);
    p.cC = p.dx - 100 / 3;
    p.hg = p.dy + 300 / 5;
    p.aO = -45;
    p.dr = 0;
}
function jv(aN, p, r) {
    if (r <= 0.8) {
        aj.Move(aN, p.sx + (p.cC - p.sx) * r / 0.8, p.sy + (p.hg - p.sy) * r / 0.8);
        var ba = p.aO + (p.dr - p.aO) * r / 0.8;
        aN.style[hx] = "rotate(" + ba + "deg)";
        aj.Opacity(aN, r * 1.25);
    } else {
        aj.Move(aN, p.cC + (p.dx - p.cC) * (r - 0.8) / (1 - 0.8), p.hg + (p.dy - p.hg) * (r - 0.8) / (1 - 0.8));
        aj.Opacity(aN, 1);
        aN.style[hx] = "rotate(" + 0 + "deg)";
    }
}
function mx(aN, p) {
    if (p.exit) {
        aN.style[hx] = "rotate(0deg)";
        aj.Opacity(aN, 1);
    }
}
function le(aN, p) {
    p.bv = 2;
    p.bU = 0;
    p.ac = 0;
    p.sy = parseFloat(aN.style.top) + 300;
    p.aK = 1;
    p.bk = 1;
    p.bi = 1;
    p.dy = parseFloat(aN.style.top);
    p.sx = p.dx = parseFloat(aN.style.left);
    aN.style[hx] = "scale(2, 0)";
}
function kc(aN, p) {
    aN.style[hx] = "scale(1,1)";
}
function iZ(aN, p) {
    p.sx = parseFloat(aN.style.left) - 200;
    p.dx = parseFloat(aN.style.left);
    p.ac = 0;
    p.bi = 1;
    p.sy = p.dy = parseFloat(aN.style.top);
    p.bv = 0;
    p.aK = 1;
    aN.style[hx] = "scaleX(0)";
}
function ll(aN, p) {
    if (p.exit) aN.style[hx] = "scaleX(1)";
}
function lM(aN, p) {
    p.bv = p.bU = 0;
    p.ac = 0;
    p.aO = 90;
    p.aK = p.bk = 1;
    p.bi = 1;
    p.dr = 0;
    p.dx = p.sx = parseFloat(aN.style.left);
    p.dy = p.sy = parseFloat(aN.style.top);
    aN.style[hx] = "rotate(90deg) scale(0,0)";
}
function nc(aN, p) {
    if (p.exit) {
        aN.style[hx] = "rotate(0deg) scale(1,1)";
    }
}
function lY(aN, p) {
    if (p.exit) {
        p.sx = aS;
    } else {
        p.sx = -aN.offsetWidth;
    }
    p.dx = parseFloat(aN.style.left);
    aN.style["transform-origin"] = "0 100% 0";
}
function nf(aN, p, r) {
    if (p.exit == 1) {
        if (r > 0.7) {
            hf = -(1 - r) * (1 - r) * 1.2 / 0.09;
            aN.style[hx] = "matrix(1,0," + hf + ",1,0,0)";
        }
        aN.style.left = (p.sx + (p.dx - p.sx) * r) + "px";
    } else {
        if (r <= 0.5) {
            aN.style.left = (p.sx + (p.dx - p.sx) * r / 0.5) + "px";
        } else {
            hf = -(0.75 - r) * (0.75 - r) * 1.2 / 0.0625 + 1.2;
            aN.style[hx] = "matrix(1,0," + ( - hf) + ",1,0,0)";
        }
    }
}
function lF(aN, p) {
    aN.style[hx] = "";
}
function kA(aN, p) {
    aN.style[hx] = "";
}
function ng(aN, p) {
    p.sy = parseFloat(aN.style.top) + 300;
    p.dy = parseFloat(aN.style.top);
    p.ac = 0;
    p.bi = 1;
    p.bv = p.bU = 0;
    p.aK = p.bk = 1.5;
    aN.style.top = p.sy + "px";
}
function kb(aN, p, r) {
    if (r >= 0 && r <= 0.5) {
        aj.Opacity(aN, p.ac + (p.bi - p.ac) * r / 0.5);
        aj.Scale(aN, p.bv + (p.aK - p.bv) * r / 0.5, p.bU + (p.bk - p.bU) * r / 0.5);
    } else {
        aN.style.top = (p.sy + (p.dy - p.sy) * (r - 0.5) / 0.5) + "px";
        aj.Scale(aN, p.aK + (1 - p.aK) * (r - 0.5) / 0.5, p.bk + (1 - p.bk) * (r - 0.5) / 0.5);
        aj.Opacity(aN, p.bi);
    }
}
function mr(aN, p) {
    if (p.exit) aN.style[hx] = "scale(1,1)";
}
function mP(aN, p) {
    p.dx = p.sx = parseFloat(aN.style.left);
    p.dy = p.sy = parseFloat(aN.style.top);
    p.bv = p.bU = 0;
    p.ac = 0;
    p.aO = 90;
    p.aK = p.bk = 1;
    p.bi = 1;
    p.dr = -360;
    aN.style[hx] = "rotate(" + p.aO + "deg)";
}
function mG(aN, p) {
    if (p.exit) aN.style[hx] = "scale(1,1) rotate(0deg)";
}
function io(F, b, c, d) {
    return c * ((F = F / d - 1) * F * ((1.70158 + 1) * F + 1.70158) + 1) + b;
}
function lE(aN, p) {
    p.sy = parseFloat(aN.style.top) + 800;
    p.dy = parseFloat(aN.style.top);
}
function ns(aN, p, r) {
    var ratio = io(r * p.duration, 0, 1, p.duration);
    aN.style.top = (p.sy + (p.dy - p.sy) * ratio) + "px";
}
function lH(aN, p) {
    p.sx = parseFloat(aN.style.left) - 500;
    p.ga = parseFloat(aN.style.left) + 500;
    p.he = parseFloat(aN.style.left) + 250;
    p.dx = parseFloat(aN.style.left);
    p.ac = 0;
    p.bi = 1;
    p.dr = 0;
    p.aO = 90;
    aj.Rotate(aN, p.aO);
}
function lU(aN, p, r) {
    if (r <= 0.4) {
        aN.style.left = (p.sx + (p.ga - p.sx) * r / 0.4) + "px";
        aj.Opacity(aN, p.ac + (p.bi - p.ac) * r / 0.4);
    } else if (r > 0.4 && r <= 0.7) {
        aj.Opacity(aN, 1);
        aN.style.left = (p.ga + (p.he - p.ga) * (r - 0.4) / 0.3) + "px";
    } else {
        aj.Opacity(aN, 1);
        aN.style.left = (p.he + (p.dx - p.he) * (r - 0.7) / 0.3) + "px";
        aj.Rotate(aN, p.aO + (p.dr - p.aO) * (r - 0.7) / 0.3);
    }
}
function lG(aN, p) {
    if (p.exit) aj.Rotate(aN, 0);
}
function lu(aN, p) {
    p.bv = p.bU = 0;
    p.ac = 0;
    p.aO = 90;
    p.aK = p.bk = 1;
    p.bi = 1;
    p.dr = -360;
}
function kq(aN, p) {
    if (p.exit) aN.style[hx] = "scale(1,1) rotate(0deg)";
}
function lo(aN, p) {
    p.dx = parseFloat(aN.style.left);
    p.dy = parseFloat(aN.style.top);
    aN.style[hx] = "scale(0,0)";
}
function kK(aN, p, r) {
    var radius = 350 * (0.1 + 1 - r);
    if (r == 1) radius = 0;
    if (p.exit == 0) {
        aj.Move(aN, radius * Math.cos(r * Math.PI * 1.5 + Math.PI) + p.dx, radius * Math.sin(r * Math.PI * 1.5 + Math.PI) + p.dy);
        aj.Scale(aN, r, r);
    } else {
        aj.Move(aN, -radius * Math.cos(r * Math.PI * 1.5 + Math.PI / 2) + p.dx, radius * Math.sin(r * Math.PI * 1.5 + Math.PI / 2) + p.dy);
        aj.Scale(aN, r, r);
    }
}
function mT(aN, p) {
    if (p.exit) aN.style[hx] = "scale(1,1)";
}
function jU(aN, p) {
    if (p.dx == undefined) {
        switch (p.dir) {
        case 4:
            {
                p.dx = p.sx = parseFloat(aN.style.left);
                p.dy = p.sy = parseFloat(aN.style.top);
                p.bv = 0;
                p.aK = 1;
                aN.style.left = p.sx + "px";
                aN.style[gf] = "left center 0";
                break;
            }
        case 2:
            {
                p.sx = parseFloat(aN.style.left) + aN.offsetWidth;
                p.dx = parseFloat(aN.style.left);
                p.dy = p.sy = parseFloat(aN.style.top);
                p.bv = 0;
                p.aK = 1;
                aN.style.left = p.sx + "px";
                aN.style[gf] = "left center 0";
                break;
            }
        case 3:
            {
                p.dx = p.sx = parseFloat(aN.style.left);
                p.sy = parseFloat(aN.style.top) + aN.offsetHeight;
                p.dy = parseFloat(aN.style.top);
                p.bU = 0;
                p.bk = 1;
                aN.style.top = p.sy + "px";
                aN.style[gf] = "0 0";
                break;
            }
        case 1:
            {
                p.dx = p.sx = parseFloat(aN.style.left);
                p.dy = p.sy = parseFloat(aN.style.top);
                p.bU = 0;
                p.bk = 1;
                break;
            }
        default:
            {
                p.sx = p.dx = parseFloat(aN.style.left);
                p.dy = p.sy = parseFloat(aN.style.top);
                p.bv = 0;
                p.aK = 1;
                break;
            }
        }
    } else {
        switch (p.dir) {
        case 2:
            aN.style.left = p.sx + "px";
            break;
        case 3:
            aN.style.top = p.sy + "px";
            break;
        }
    }
}
function jI(aN, p) {
    p.dy = parseFloat(aN.style.top);
    p.sy = p.exit ? 400 - p.dy: -400;
    aN.style[hx] = "rotate(-30deg)";
}
function kv(aN, p, r) {
    if (p.exit) {
        var ratio = io(r * p.duration, 0, 1, p.duration);
        aN.style.top = (p.sy + (p.dy - p.sy) * ratio) + "px";
        var ba = 30 * (1 - ratio);
        aN.style[hx] = "rotate(" + ba + "deg)";
        return;
    }
    var ratio = ip(r, 0, 1, 1);
    aN.style.top = (p.sy + (p.dy - p.sy) * ratio) + "px";
    if (r < 1 / 2.75) {} else if (r < 2 / 2.75) {
        var ba = 124 * r - 75;
        aN.style[hx] = "rotate(" + ba + "deg)";
    } else if (r < 2.5 / 2.75) {
        var ba = -(r - 2 / 2.75) * 50 * 2.75 + 15;
        aN.style[hx] = "rotate(" + ba + "deg)";
    } else {
        var ba = (r - 2.5 / 2.75) * 40 * 2.75 - 10;
        aN.style[hx] = "rotate(" + ba + "deg)";
        if (r == 1) {
            aN.style[hx] = "";
        }
    }
    aN.style.display = "none";
    aN.offetHeight;
    aN.style.display = "";
}
function gY(aN, p) {
    switch (p.dir) {
    case 17:
        p.am = "scaleY(";
        break;
    default:
        p.am = "scaleX(";
        break;
    }
}
function hY(aN, p, r) {
    aj.Opacity(aN, r);
    var fu = 0;
    if (r < 0.2) {
        fu = r * 5;
    } else if (r < 0.4) {
        fu = 1 - (r - 0.2) * 5;
    } else if (r < 0.6) {
        fu = -(r - 0.4) * 5;
    } else if (r < 0.8) {
        fu = (r - 0.8) * 5;
    } else {
        fu = (r - 0.8) * 5;
    }
    aN.style[hx] = p.am + fu + ")";
}
function kk(aN, p) {
    if (p.exit) aN.style[hx] = p.am + "1)";
}
function lv(aN, p) {
    p.bv = 1.5;
    p.aK = 1;
    p.bU = 0;
    p.bk = 1;
    p.sx = parseFloat(aN.style.left) - 400;
    p.dx = parseFloat(aN.style.left);
    aN.style[hx] = "scale(" + p.bv + "," + p.bU + ")";
}
function jY(aN, p, r) {
    if (r <= 0.8) {} else {
        aj.Scale(aN, p.bv + (p.aK - p.bv) * (r - 0.8) / 0.2, p.bU + (p.bk - p.bU) * (r - 0.8) / 0.2);
    }
}
function lf(aN, p) {
    p.sx = parseFloat(aN.style.left) - 200;
    p.dx = parseFloat(aN.style.left);
}
function jL(aN, p, r) {
    aN.style.left = (p.sx + (p.dx - p.sx) * r) + "px";
}
function ki(aN, p) {
    p.sx = parseFloat(aN.style.left);
    p.cC = parseFloat(aN.style.left) + 100;
    p.dx = parseFloat(aN.style.left);
    p.cf = 0;
    p.nj = 1;
    p.ac = 0;
    p.bi = 1;
}
function kE(aN, p, r) {
    if (r <= 0.45) {
        aN.style.left = (p.sx + (p.cC - p.sx) * r / 0.45) + "px";
        aj.Scale(aN, r / 0.45, r / 0.45);
        aj.Opacity(aN, r / 0.45);
    } else {
        aj.Opacity(aN, 1);
        aj.Scale(aN, 1, 1);
        aN.style.left = (p.cC + (p.dx - p.cC) * (r - 0.45) / 0.55) + "px";
    }
}
function kC(aN, p) {
    p.bv = p.bU = 0;
    p.aK = p.bk = 1;
    p.ac = 0;
    p.bi = 1;
    p.sx = parseFloat(aN.style.left);
    p.sy = parseFloat(aN.style.top);
    switch (p.dir) {
    case 30:
        p.sx += aN.offsetWidth / 2;
        p.sy += aN.offsetHeight;
        break;
    case 32:
        p.bv = p.bU = 0.5;
        break;
    case 20:
        p.bv = p.bU = 10;
        break;
    case 34:
        p.bv = p.bU = 10;
        p.sy = p.sy + aN.offsetHeight;
        break;
    case 29:
        p.bv = p.bU = 2;
        break;
    default:
        p.sx = parseFloat(aN.style.left);
        p.sy = parseFloat(aN.style.top);
    }
    p.dx = parseFloat(aN.style.left);
    p.dy = parseFloat(aN.style.top);
}
function nr(src, ej, r) {
    var fw = new Array;
    for (var i = 0; i < 3; i++) {
        var iW = (src >> (i * 8)) & 0x0000ff;
        var kB = (ej >> (i * 8)) & 0x0000ff;
        var bG = Math.floor(iW + (kB - iW) * r) & 0xff;
        fw.push(bG);
    }
    return fw[0] | (fw[1] << 8) | (fw[2] << 16);
}
function mf(aN, p) {
    p.aH = cv(aN, p);
    p.di = dP(aN);
    aN.style[hx] = "scaleY(1)";
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function mw(aN, p, r) {
    if (r < 0.2) {
        var sy = 25 * (r - 0.2) * (r - 0.2);
        aN.style[hx] = "scaleY(" + sy + ")";
    } else if (r < 0.3) {
        var sy = 15 * r - 3;
        aN.style[hx] = "scaleY(" + sy + ")";
    } else {
        aN.style[hx] = "scaleY(1.5)";
    }
    cV(p, dF(p.aH, p.aQ, r));
    var hS = dF(p.di, p.aQ, r);
    ad(aN, "color", "#" + hS.toString(16));
}
function jj(aN, p) {}
function ig(aN, p, r) {
    if (r < 0.3) {} else if (r < 0.5) {
        aN.style.visibility = "hidden";
    } else {
        aN.style.visibility = "";
    }
}
function jp(aN, p) {}
function js(aN, p, r) {
    if (r > 0.3 && r < 0.7) {
        ad(aN, "font-weight", "bold");
    } else {
        ad(aN, "font-weight", "");
    }
}
function jD(aN, p) {
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function la(aN, p, r) {
    if (r > 0.1) {
        ad(aN, "font-weight", "bold");
    } else {
        ad(aN, "font-weight", "");
    }
}
function jQ(aN, p) {
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function ke(aN, p, r) {
    if (r > 0.1) {
        ad(aN, "color", "#" + p.aQ.toString(16));
    }
}
function ly(aN, p) {
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function jM(aN, p, r) {
    if (r > 0.1) {
        ad(aN, "text-decoration", "underline");
    }
}
function ky(aN, p) {
    p.dH = cv(aN, p);
}
function ln(aN, p, r) {
    var aQ = dF(p.dH, p.aQ, r);
    cV(p, aQ);
}
function ko(aN, p) {
    cV(p, p.dH);
}
function kt(aN, p) {
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function jT(aN, p, r) {
    if (r > 0.1) {
        ad(aN, "font-family", p.na);
    }
}
function aI(aN, p) {
	if(p.ih){
		if(aN.parentNode.className == 'cvs'){//drawcanvas and left/top are moved upwards,
			p.ih = p.ih.replace(/left[^;]*;/g,"");
			p.ih = p.ih.replace(/top[^;]*;/g,"")
		}
		aN.style.cssText = p.ih;
	}
}
function il(aN, p) {
    p.tc = dP(aN);
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function hZ(aN, p, r) {
    var aQ = dF(p.tc, p.aQ, r);
    ad(aN, "color", "#" + aQ.toString(16));
}
function kT(aN, p) {
    var aa = aN.getElementsByTagName("span");
    if (aa.length > 0) {
        p.cA = aa[0].style["font-size"];
        if (p.cA) p.cA = parseInt(p.cA);
    } else if (aN.tagName.toLowerCase() == "span") {
        p.cA = aN.style["font-size"];
        if (p.cA) p.cA = parseInt(p.cA);
    }
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function lq(aN, p, r) {
    var fw = Math.floor(p.cA * ((p.size - 1) * r + 1));
    ad(aN, "font-size", fw + "px");
}
function kX(aN, p) {
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function kz(aN, p, r) {
    if (r > 0.1) {
        var aa = aN.getElementsByTagName("SPAN");
        if (aa.length == 0 && aN.tagName.toLowerCase() == "span") {
            if (p.ev & 0x1) {
                aN.style["font-weight"] = "bold";
            }
            if (p.ev & 0x2) {
                aN.style["font-style"] = "italic";
            }
            if (p.ev & 0x4) {
                aN.style["text-decoration"] = "underline";
            }
            return;
        }
        for (var i = 0; i < aa.length; i++) {
            if (p.ev & 0x1) {
                aa[i].style["font-weight"] = "bold";
            }
            if (p.ev & 0x2) {
                aa[i].style["font-style"] = "italic";
            }
            if (p.ev & 0x4) {
                aa[i].style["text-decoration"] = "underline";
            }
        }
    }
}
function kY(aN, p) {
    p.iY = jF(aN, p);
}
function jX(aN, p, r) {
    var aQ = dF(p.iY, p.aQ, r);
    iO(p, aQ);
}
function kR(aN, p) {
    iO(p, p.iY);
}
function jJ(aN, p) {
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function kG(aN, p, r) {
    if (r > 0.1) {
        ad(aN, "color", "#" + p.aQ.toString(16));
    }
}
function kn(aN, p) {
    p.dH = cv(aN, p);
    p.dc = ((p.dH << 8) & 0xffff00) | ((p.dH >> 8) & 0xff)
}
function iz(aO, aM, aw, dr, ff, eV, r) {
    var bG = 0;
    var aQ;
    if (r < 1 / 3) {
        bG = aO + (dr - aO) * r * 3;
        aQ = ((bG << 16) | (aM << 8) | aw);
    } else if (r < 2 / 3) {
        bG = aw + (eV - aw) * (r - 1 / 3) * 3;
        aQ = ((dr << 16) | (aM << 8) | bG);
    } else {
        bG = aM + (ff - aM) * (r - 2 / 3) * 3;
        aQ = ((dr << 16) | (bG << 8) | eV);
    }
    return aQ;
}
function kl(aN, p, r) {
    var aQ = iz((p.dH >> 16) & 0xff, (p.dH >> 8) & 0xff, p.dH & 0xff, (p.dc >> 16) & 0xff, (p.dc >> 8) & 0xff, p.dc & 0xff, r);
    cV(p, aQ);
}
function ld(aN, p) {
    cV(p, p.dH);
}
function lA(aN, p) {
    p.dH = cv(aN, p);
    p.aO = (p.dH >> 16) & 0xff;
    p.aM = (p.dH >> 8) & 0xff;
    p.aw = p.dH & 0xff;
}
function kZ(p, r) {
    var bG = 0;
    if (r < 1 / 6) {
        bG = p.aM + (p.aO - p.aM) * r * 6;
        bG = ((p.aO << 16) | (bG << 8) | p.aw);
    } else if (r < 1 / 3) {
        bG = p.aO + (p.aw - p.aO) * (r - 1 / 6) * 6;
        bG = ((bG << 16) | (p.aO << 8) | p.aw);
    } else if (r < 1 / 2) {
        bG = p.aw + (p.aM - p.aw) * (r - 1 / 3) * 6;
        bG = ((p.aw << 16) | (p.aO << 8) | bG);
    } else if (r < 2 / 3) {
        bG = p.aM + (p.aO - p.aM) * (r - 1 / 2) * 6;
        bG = ((p.aw << 16) | (p.aO << 8) | bG);
    } else if (r < 5 / 6) {
        bG = p.aO + (p.aw - p.aO) * (r - 2 / 3) * 6;
        bG = ((p.aw << 16) | (bG << 8) | p.aO);
    } else {
        bG = p.aw + (p.aM - p.aw) * (r - 5 / 6) * 6;
        bG = ((bG << 16) | (p.aw << 8) | p.aO);
    }
    return bG;
}
function ku(aN, p, r) {
    var aQ = kZ(p, r);
    cV(p, aQ);
}
function jN(aN, p) {
    cV(p, p.dH);
}
function iq(fill) {
    aO = (fill >> 16) & 0x0000ff;
    aM = (fill >> 8) & 0x0000ff;
    aw = fill & 0x0000ff;
    var max, min;
    max = min = aO;
    if (max < aM) {
        max = aM;
    }
    if (max < aw) {
        max = aw;
    }
    if (min > aM) {
        min = aM;
    }
    if (min > aw) {
        min = aw;
    }
    dr = max + min - aO;
    ff = max + min - aM;
    eV = max + min - aw;
    return (dr << 16) | (ff << 8) | eV;
}
function lB(aN, p) {
    p.aH = cv(aN, p);
    p.fr = iq(p.aH);
    p.di = dP(aN);
    p.eA = iq(p.di);
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function kU(aN, p, r) {
    var bG;
    if (r < 1 / 3) {
        var aM = (p.aH >> 8) & 0xff;
        var ff = (p.fr >> 8) & 0xff;
        bG = Math.floor(aM + (ff - aM) * r * 3);
        cV(p, (p.aH & 0xff00ff) | (bG << 8));
        aM = (p.di >> 8) & 0xff;
        ff = (p.eA >> 8) & 0xff;
        bG = Math.floor(aM + (ff - aM) * r * 3);
        ad(aN, "color", "#" + ((p.di & 0xff00ff) | (bG << 8)).toString(16));
    } else if (r < 2 / 3) {
        var aO = (p.aH >> 16) & 0xff;
        var dr = (p.fr >> 16) & 0xff;
        bG = Math.floor(aO + (dr - aO) * (r - 1 / 3) * 3);
        cV(p, (p.aH & 0x0000ff) | (bG << 16) | (p.fr & 0x00ff00));
        aO = (p.di >> 16) & 0xff;
        dr = (p.eA >> 16) & 0xff;
        bG = Math.floor(aO + (dr - aO) * (r - 1 / 3) * 3);
        ad(aN, "color", "#" + ((p.di & 0x0000ff) | (bG << 16) | (p.eA & 0x00ff00)).toString(16));
    } else {
        var aw = p.aH & 0xff;
        var eV = p.fr & 0xff;
        bG = Math.floor(aw + (eV - aw) * (r - 2 / 3) * 3);
        cV(p, (p.aH & 0x0000ff) | (bG << 16) | (p.fr & 0x00ff00));
        aO = p.di & 0xff;
        dr = p.eA & 0xff;
        bG = Math.floor(aw + (eV - aw) * (r - 2 / 3) * 3);
        ad(aN, "color", "#" + ((p.eA & 0xffff00) | (bG)).toString(16));
    }
}
function dj(R, G, B) {
    var max, min, gg, hv, hy, dq;
    R = R / 255.0;
    G = G / 255.0;
    B = B / 255.0;
    min = Math.min(R, G, B);
    max = Math.max(R, G, B);
    dq = max - min;
    var L, H, S;
    L = (max + min) / 2.0;
    if (dq == 0) {
        H = 0;
        S = 0;
    } else {
        if (L < 0.5) S = dq / (max + min);
        else S = dq / (2 - max - min);
        gg = (((max - R) / 6.0) + (dq / 2.0)) / dq;
        hv = (((max - G) / 6.0) + (dq / 2.0)) / dq;
        hy = (((max - B) / 6.0) + (dq / 2.0)) / dq;
        if (R == max) H = hy - hv;
        else if (G == max) H = (1.0 / 3.0) + gg - hy;
        else if (B == max) H = (2.0 / 3.0) + hv - gg;
        if (H < 0) H += 1;
        if (H > 1) H -= 1;
    }
    return {
        l: L,
        h: H,
        s: S
    };
}
function lJ(dZ, cK, dR) {
    var min = Math.min(dZ, cK, dR);
    var max = Math.max(dZ, cK, dR);
    var iC = max / 255;
    var jE = max == 0 ? 0 : (max - min) / max;
    var eh = 0;
    if (max == dZ && cK >= dR) {
        eh = (cK - dR) * 60 / (max - min) + 0;
    } else if (max == dZ && cK < dR) {
        eh = (cK - dR) * 60 / (max - min) + 360;
    } else if (max == cK) {
        eh = (dR - dZ) * 60 / (max - min) + 120;
    } else if (max == dR) {
        eh = (dZ - cK) * 60 / (max - min) + 240;
    }
    return {
        h: eh,
        s: jE,
        b: iC
    };
}
function nl(cj) {
    var r = 0;
    var g = 0;
    var b = 0;
    var i = (hm(cj.h / 60)) % 6;
    var f = (cj.h / 60) - i;
    var p = cj.b * (1 - cj.s);
    var q = cj.b * (1 - f * cj.s);
    var F = cj.b * (1 - (1 - f) * cj.s);
    switch (i) {
    case 0:
        r = cj.b;
        g = F;
        b = p;
        break;
    case 1:
        r = q;
        g = cj.b;
        b = p;
        break;
    case 2:
        r = p;
        g = cj.b;
        b = F;
        break;
    case 3:
        r = p;
        g = q;
        b = cj.b;
        break;
    case 4:
        r = F;
        g = p;
        b = cj.b;
        break;
    case 5:
        r = cj.b;
        g = p;
        b = q;
        break;
    default:
        break;
    }
    return {
        r:
        (hm)(r * 255.0),
        g: (hm)(g * 255.0),
        b: (hm)(b * 255.0)
    };
}
function gC(p, q, F) {
    if (F < 0) F += 1;
    if (F > 1) F -= 1;
    if (F < 1 / 6) return p + (q - p) * 6 * F;
    if (F < 1 / 2) return q;
    if (F < 2 / 3) return p + (q - p) * (2 / 3 - F) * 6;
    return p;
}
function mQ(H, S, L) {
    var R, G, B;
    var ez, eU;
    if (S == 0) {
        R = L * 255.0;
        G = L * 255.0;
        B = L * 255.0;
    } else {
        if (L < 0.5) eU = L * (1 + S);
        else eU = (L + S) - (S * L);
        ez = 2.0 * L - eU;
        R = 255.0 * gC(ez, eU, H + (1.0 / 3.0));
        G = 255.0 * gC(ez, eU, H);
        B = 255.0 * gC(ez, eU, H - (1.0 / 3.0));
    }
    return {
        r: R,
        g: G,
        b: B
    };
}
function ir(aN, p) {
    var aH = cv(aN, p);
    p.cr = dj((aH >> 16) & 0xff, (aH >> 8) & 0xff, (aH) & 0xff);
    p.bn = {
        h: p.cr.h,
        s: p.cr.s - 0.12,
        l: p.cr.l - 0.25
    };
    if (p.bn.s < 0) p.bn.s = 0;
    if (p.bn.l < 0) p.bn.l = 0;
    aH = dP(aN);
    p.bY = dj((aH >> 16) & 0xff, (aH >> 8) & 0xff, (aH) & 0xff);
    p.cg = {
        h: p.bY.h,
        s: p.bY.s - 0.12,
        l: p.bY.l - 0.25
    };
    if (p.cg.s < 0) p.cg.s = 0;
    if (p.cg.l < 0) p.cg.l = 0;
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function iR(src, ej, r) {
    var l = src.l + (ej.l - src.l) * r;
    var s = src.s + (ej.s - src.s) * r;
    var h = src.h + (ej.h - src.h) * r;
    var ah = mQ(h, s, l);
    return (ah.r << 16) | (ah.g << 8) | ah.b;
}
function eO(aN, p, r) {
    var aQ = iR(p.cr, p.bn, r);
    cV(p, aQ);
    aQ = iR(p.bY, p.cg, r);
    ad(aN, "color", "#" + aQ.toString(16));
}
function jl(aN, p) {
    var aH = cv(aN, p);
    p.cr = dj((aH >> 16) & 0xff, (aH >> 8) & 0xff, (aH) & 0xff);
    p.bn = {
        h: p.cr.h,
        s: 0,
        l: p.cr.l - 0.25
    };
    aH = dP(aN);
    p.bY = dj((aH >> 16) & 0xff, (aH >> 8) & 0xff, (aH) & 0xff);
    p.cg = {
        h: p.bY.h,
        s: 0,
        l: p.bY.l - 0.25
    };
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function iA(aN, p) {
    aN.style[hx] = "scale(1,1)";
}
function hT(aN, p, r) {
    if (r < 0.3) {
        aj.Opacity(aN, -5 / 3 * r + 1);
    } else if (r < 0.7) {
        aj.Opacity(aN, 0.5);
        aj.Scale(aN, 1 + 0.1 * Math.sin(2.5 * Math.PI * (r - 0.3)), 1 + 0.1 * Math.sin(2.5 * Math.PI * (r - 0.3)));
    } else {
        aj.Opacity(aN, 5 / 3 * r - 2 / 3);
        aN.style[hx] = "scale(1,1)";
    }
}
function iT(aN, p) {
    var aH = cv(aN, p);
    p.cr = dj((aH >> 16) & 0xff, (aH >> 8) & 0xff, (aH) & 0xff);
    p.bn = {
        h: p.cr.h,
        s: 0,
        l: 1
    };
    aH = dP(aN);
    p.bY = dj((aH >> 16) & 0xff, (aH >> 8) & 0xff, (aH) & 0xff);
    p.cg = {
        h: p.bY.h,
        s: 0,
        l: 1
    };
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function jg(aN, p, r) {
    if (r < 0.5) {
        eO(aN, p, r * 2);
    } else {
        eO(aN, p, 2 * (1 - r));
    }
}
function mC(aN, p) {
    if (p.sx !== undefined && p.sy !== undefined) {
        aN.style[hx] = "scale(1,1)";
    } else if (p.sx !== undefined) {
        aN.style[hx] = "scaleX(1)";
    } else {
        aN.style[hx] = "scaleY(1)";
    }
}
function lZ(aN, p, r) {
    if (p.sx !== undefined && p.sy !== undefined) {
        aj.Scale(aN, 1 + (p.sx / 100 - 1) * r, 1 + (p.sy / 100 - 1) * r);
    } else if (p.sx !== undefined) {
        aj.hX(aN, 1 + (p.sx / 100 - 1) * r);
    } else {
        aj.fc(aN, 1 + (p.sy / 100 - 1) * r);
    }
}
function mK(aN, p) {
    if (p.sx !== undefined && p.sy !== undefined) {
        aN.style[hx] = "scale(1,1)";
    } else if (p.sx !== undefined) {
        aN.style[hx] = "scaleX(1)";
    } else {
        aN.style[hx] = "scaleY(1)";
    }
}
function mo(aN, p) {
    p.aH = cv(aN, p);
    p.di = dP(aN);
    var bu = window.getComputedStyle(aN, null);
    p.cA = parseInt(bu["font-size"]);
    if (aN.className == "sub") {
        p.cL = parseFloat(aN.style.left);
    }
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function mX(aN, p, r) {
    cV(p, dF(p.aH, p.aQ, r));
    ad(aN, "color", "#" + (dF(p.di, p.aQ, r)).toString(16));
    ad(aN, "font-size", Math.floor(p.cA * (1 + r)) + "px");
    if (aN.className == "sub") {
        aN.style.left = p.cL * (1 + r) + "px";
    }
}
function kV(aN, p) {
    var aH = cv(aN, p);
    p.cr = dj((aH >> 16) & 0xff, (aH >> 8) & 0xff, (aH) & 0xff);
    p.bn = {
        h: p.cr.h,
        s: p.cr.s + 0.12,
        l: p.cr.l + 0.25
    };
    if (p.bn.s > 1) p.bn.s = 1;
    if (p.bn.l > 1) p.bn.l = 1;
    aH = dP(aN);
    p.bY = dj((aH >> 16) & 0xff, (aH >> 8) & 0xff, (aH) & 0xff);
    p.cg = {
        h: p.bY.h,
        s: p.bY.s + 0.12,
        l: p.bY.l + 0.25
    };
    if (p.cg.s > 1) p.cg.s = 1;
    if (p.cg.l > 1) p.cg.l = 1;
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function nb(aN, p) {
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function nh(aN, p, r) {
    var ry = -10 * Math.sin(Math.PI * r);
    var hj = -10 * Math.sin(Math.PI * r);
    aN.style[hx] = "rotateY(" + ry + "deg) rotateZ(" + hj + "deg)";
}
function kW(aN, p) {
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
    p.gl = aj.iX(aN);
}
function mH(aN, p, r) {
    aN.style[hx] = "rotate(" + (p.gl + parseFloat(p.amt) * r) + "deg)";
}
function jK(aN, p) {
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function lx(aN, p, r) {
    if (r > 0.1) {
        var aa = aN.getElementsByTagName("SPAN");
        if (aa.length > 0) {
            for (var i = 0; i < aa.length; i++) {
                aa[i].style["text-decoration"] = "underline";
                aa[i].style["font-weight"] = "bold";
                aa[i].style["font-style"] = "italic";
                aa[i].style["color"] = p.aQ;
            }
        } else {
            if (aN.tagName.toLowerCase() == "span") {
                aN.style["text-decoration"] = "underline";
                aN.style["font-weight"] = "bold";
                aN.style["font-style"] = "italic";
                aN.style["color"] = p.aQ;
            }
        }
    }
}
function kD(aN, p) {}
function kQ(aN, p, r) {
    var rt = 0;
    var angle = 4;
    if (r <= 0.08) {
        rt = angle * r / 0.08;
    } else if (r > 0.12 && r <= 0.28) {
        rt = -angle * (r - 0.12) / 0.08;
    } else if (r > 0.32 && r <= 0.48) {
        rt = angle * (r - 0.32) / 0.08;
    } else if (r > 0.52 && r <= 0.68) {
        rt = -angle * (r - 0.52) / 0.08;
    } else if (r > 0.72 && r <= 0.88) {
        rt = angle * (r - 0.72) / 0.08;
    } else if (r > 0.92 && r <= 0.98) {
        rt = -angle * (r - 0.9) / 0.1;
    } else {
        rt = 0;
    }
    aj.Rotate(aN, rt);
}
function lj(aN, p) {}
function km(aN, p, r) {
    aj.Opacity(aN, 1 - p.amt);
}
function kP(aN, p) {
    aj.Opacity(aN, 1);
}
function jO(aN, p) {
    p.aH = cv(aN, p);
    aN.style[hx] = "sclY(1)";
    if (p.hasOwnProperty("ih") == false) p.ih = aN.style.cssText;
}
function kh(aN, p, r) {
    cV(p, dF(p.aH, p.aQ, r));
    aj.fc(aN, 1 + r / 2);
    var aa = aN.getElementsByTagName("SPAN");
    if (aa.length > 0) {
        for (var i = 0; i < aa.length; i++) {
            aj.Opacity(aa[i], 1 - r);
        }
    } else {
        aj.Opacity(aN, 1 - r);
    }
}
function kx(aN, p) {
    if (p.path != null) {
        var gz = p.path.split(" ");
        if (gz.length > 6) {
            p.de = parseFloat(aN.style.top) + Number(gz[5]);
            p.ck = parseFloat(aN.style.left) + Number(gz[4]);
            p.cE = parseFloat(aN.style.left);
            p.dE = parseFloat(aN.style.top);
        }
    }
    aN.style[hx] = "rotate(0deg)";
}
function kp(aN, p, r) {
    if (r < 0.8) {
        var cy = p.dE + (p.de - p.dE) * (0.16 - (r - 0.4) * (r - 0.4)) / 0.16;
        var cx = p.cE + (p.ck - p.cE) * (0.16 - (r - 0.4) * (r - 0.4)) / 0.16;
    } else {
        cy = p.dE;
        cx = p.cE;
    }
    aj.Move(aN, cx, cy);
    aj.Rotate(aN, 20 * Math.sin(2 * Math.PI * r));
}
function hJ(m) {
    this.aC = dh(m);
    this.J = bF(this.aC) + 1;
    var cq = new bo();
    cq.df[2 * this.J] = 1;
    this.gu = eK(cq, this.aC);
    this.eB = new bo();
    this.eB.df[this.J + 1] = 1;
    this.hd = fP;
    this.eH = gF;
    this.ei = hb;
}
function fP(x) {
    var dW = eb(x, this.J - 1);
    var ey = aB(dW, this.gu);
    var eL = eb(ey, this.J + 1);
    var r1 = ee(x, this.J + 1);
    var fo = aB(eL, this.aC);
    var r2 = ee(fo, this.J + 1);
    var r = aR(r1, r2);
    if (r.C) {
        r = aV(r, this.eB);
    }
    var dO = bd(r, this.aC) >= 0;
    while (dO) {
        r = aR(r, this.aC);
        dO = bd(r, this.aC) >= 0;
    }
    return r;
}
function gF(x, y) {
    var gq = aB(x, y);
    return this.hd(gq);
}
function hb(x, y) {
    var result = new bo();
    result.df[0] = 1;
    var a = x;
    var J = y;
    while (true) {
        if ((J.df[0] & 1) != 0) result = this.eH(result, a);
        J = ci(J, 1);
        if (J.df[0] == 0 && bF(J) == 0) break;
        a = this.eH(a, a);
    }
    return result;
}
function fO() {
    var bB = this;
    var mode = 0;
    var gk = 0;
    var gy = 20;
    this.fv = function() {
        bB.bW = eJ(1);
    };
    this.cR = function(ratio, sp) {
        var right = dS(ratio);
        sp.x = eS(right);
        sp.y = kr(right);
    };
    function eS(u) {
        var bb = 1 - u;
        return Math.pow(bb, 3) * bB.dJ.x + 3 * bb * bb * u * bB.bq.x + 3 * bb * u * u * bB.ax.x + Math.pow(u, 3) * bB.bQ.x;
    };
    function kr(u) {
        var bb = 1 - u;
        return Math.pow(bb, 3) * bB.dJ.y + 3 * bb * bb * u * bB.bq.y + 3 * bb * u * u * bB.ax.y + Math.pow(u, 3) * bB.bQ.y;
    };
    function eE(u) {
        var bb = 1 - u;
        return - 3 * bB.dJ.x * bb * bb + 3 * bB.bq.x * bb * bb - 6 * bB.bq.x * bb * u + 6 * bB.ax.x * bb * u - 3 * bB.ax.x * u * u + 3 * bB.bQ.x * u * u;
    };
    function fH(u) {
        var bb = 1 - u;
        return - 3 * bB.dJ.y * bb * bb + 3 * bB.bq.y * bb * bb - 6 * bB.bq.y * bb * u + 6 * bB.ax.y * bb * u - 3 * bB.ax.y * u * u + 3 * bB.bQ.y * u * u;
    };
    function eT(u) {
        return Math.sqrt(Math.pow(eE(u), 2) + Math.pow(fH(u), 2));
    };
    function eJ(u) {
        var gJ = 100;
        var aL = Math.ceil(gJ * u);
        if (aL == 0 || aL == -1) return 0;
        if (aL % 2 != 0) aL++;
        var eD = aL * .5;
        var dM = 0;
        var en = 0;
        var dp = u / aL;
        for (var i = 0; i < eD; i++) {
            en += eT((2 * i + 1) * dp);
            dM += eT(2 * i * dp);
        }
        return (eT(0) + eT(1) + 2 * en + 4 * dM) * dp / 3;
    };
    function dS(u) {
        var ai = u * bB.bW;
        var cZ = 0;
        do {
            var bs = eJ(u);
            var cU = eT(u);
            if (cU < 0.00001) {
                return 0;
            }
            cZ = u - (bs - ai) / cU;
            if (cU == 0) return 0;
            if (Math.abs(cZ - u) < 0.01) {
                break;
            }
            u = cZ;
        } while ( true );
        return cZ;
    }
};
var fN = function(aN, p, r) {
    function fh(path) {
        var s = path.split(" ");
        var ce = [];
        ce.push(s[0]);
        for (var i = 1; i < s.length; i++) {
            var bA = s[i];
            if (bA.charCodeAt(0) > 60) {
                p.aU.push(ce);
                ce = [];
            } else {
                s[i] = parseFloat(s[i]);
            }
            ce.push(s[i]);
        }
        var av = 0;
        p.aD.push(0);
        p.aD.push(av);
        for (i = 1; i < p.aU.length; i++) {
            if (p.aU[i][0] == "C" || p.aU[i][0] == "c") {
                var aT = new fO();
                aT.dJ = {
                    x: p.aU[i - 1][1],
                    y: p.aU[i - 1][2]
                };
                aT.bq = {
                    x: p.aU[i][1],
                    y: p.aU[i][2]
                };
                aT.ax = {
                    x: p.aU[i][3],
                    y: p.aU[i][4]
                };
                aT.bQ = {
                    x: p.aU[i][5],
                    y: p.aU[i][6]
                };
                aT.fv();
                av += aT.bW;
                p.aD.push(av);
                p.aU[i][1] = aT.bQ.x;
                p.aU[i][2] = aT.bQ.y;
                p.aU[i][3] = aT;
                p.aU[i].splice(4, 3);
            } else {
                var cE = p.aU[i - 1][1];
                var dE = p.aU[i - 1][2];
                var ck = p.aU[i][1];
                var de = p.aU[i][2];
                var hc = Math.sqrt((ck - cE) * (ck - cE) + (de - dE) * (de - dE));
                av += hc;
                p.aD.push(av);
            }
        }
        p.hD = av;
    }
	if(aN.parentNode.className == "cvs"){
		p.o=aN=aN.parentNode;
	}
    if (!p.aD || !p.aU) {
        p.aD = [];
        p.aU = [];
        fh(p.path);
    }
    p.bC = 1;
    if (p.eff == 101) {
        if (!aN.hO) {
            aN.hO = aN.offsetLeft;
            aN.jy = aN.offsetTop;
        }
        p.aq = aN.hO;
        p.by = aN.jy;
        if (isNaN(p.aq)) p.aq = 0;
        if (isNaN(p.by)) p.by = 0;
    }
    p.reset = window.ereset["e" + p.eff];
};
var fG = function(aN, p, r) {
    function cR(pos) {
        var cE = p.aU[p.bC - 1][1];
        var dE = p.aU[p.bC - 1][2];
        var ap = {
            x: 0,
            y: 0
        };
        if (p.aU[p.bC][0] == "C" || p.aU[p.bC][0] == "c") {
            var aT = p.aU[p.bC][3];
            aT.cR(pos / aT.bW, ap);
            if (p.aq == undefined) {
                alert("aq not set");
            }
            ap.x += p.aq;
            ap.y += p.by;
        } else {
            var ratio = pos / (p.aD[p.bC + 1] - p.aD[p.bC]);
            var ck = p.aU[p.bC][1];
            var de = p.aU[p.bC][2];
            ap.x = p.aq + cE + (ck - cE) * ratio;
            ap.y = p.by + dE + (de - dE) * ratio;
        }
        aN.style.display = "none";
        aN.offsetHeight;
        aN.style.display = "";
        aN.style.left = ap.x + "px";
        aN.style.top = ap.y + "px";
    }
    function kJ(p) {
        if (p.bC >= p.aU.length) return;
        if (p.aU[p.bC][0] == "C" || p.aU[p.bC][0] == "c") {
            var aT = p.aU[p.bC][3];
            var cD = aT.dJ;
            var ca = aT.bq;
            var cz = aT.ax;
            var fF = aT.bQ;
            p.bf.beginPath();
            p.bf.moveTo(p.aq + cD.x, p.by + cD.y);
            p.bf.bezierCurveTo(p.aq + ca.x, p.by + ca.y, p.aq + cz.x, p.by + cz.y, p.aq + fF.x, p.by + fF.y);
            p.bf.stroke();
        } else {
            p.bf.beginPath();
            p.bf.moveTo(p.aq + p.aU[p.bC - 1][1], p.by + p.aU[p.bC - 1][2]);
            p.bf.lineTo(p.aq + p.aU[p.bC][1], p.by + p.aU[p.bC][2]);
            p.bf.stroke();
        }
    };
    function ks(pos) {
        var cE = p.aU[p.bC - 1][1];
        var dE = p.aU[p.bC - 1][2];
        var ap = {
            x: 0,
            y: 0
        };
        if (p.aU[p.bC][0] == "C" || p.aU[p.bC][0] == "c") {
            var ratio = pos / (p.aD[p.bC + 1] - p.aD[p.bC]);
            var aT = p.aU[p.bC][3];
            var cD = aT.dJ;
            var ca = aT.bq;
            var cz = aT.ax;
            var fF = aT.bQ;
            var ec = {
                x: cD.x + (ca.x - cD.x) * ratio,
                y: cD.y + (ca.y - cD.y) * ratio
            };
            var hA = {
                x: ca.x + (cz.x - ca.x) * ratio,
                y: ca.y + (cz.y - ca.y) * ratio
            };
            var gR = {
                x: ec.x + (hA.x - ec.x) * ratio,
                y: ec.y + (hA.y - ec.y) * ratio
            };
            var hB = {
                x: cD.x * (1 - ratio) * (1 - ratio) * (1 - ratio) + 3 * ca.x * ratio * (1 - ratio) * (1 - ratio) + cz.x * 3 * ratio * ratio * (1 - ratio) + fF.x * ratio * ratio * ratio,
                y: cD.y * (1 - ratio) * (1 - ratio) * (1 - ratio) + 3 * ca.y * ratio * (1 - ratio) * (1 - ratio) + cz.y * 3 * ratio * ratio * (1 - ratio) + fF.y * ratio * ratio * ratio
            };
            p.bf.beginPath();
            p.bf.moveTo(p.aq + cE, p.by + dE);
            p.bf.bezierCurveTo(p.aq + ec.x, p.by + ec.y, p.aq + gR.x, p.by + gR.y, p.aq + hB.x, p.by + hB.y);
            p.bf.stroke();
        } else {
            var ratio = pos / (p.aD[p.bC + 1] - p.aD[p.bC]);
            var ck = p.aU[p.bC][1];
            var de = p.aU[p.bC][2];
            ap.x = p.aq + cE + (ck - cE) * ratio;
            ap.y = p.by + dE + (de - dE) * ratio;
            p.bf.beginPath();
            p.bf.moveTo(p.aq + cE, p.by + dE);
            p.bf.lineTo(ap.x, ap.y);
            p.bf.stroke();
        }
    }
    if (r == 1) {
        if (p.eff == 101) {
            if (!p.aD || !p.aU) {
                fN(aN, p, 0);
            }
            var ai = p.aU.length;
            aN.style.left = p.aq + p.aU[ai - 1][1] + "px";
            aN.style.top = p.by + p.aU[ai - 1][2] + "px";
        } else {
            mJ(aN, p);
        }
        return;
    }
    var bH = p.hD * r;
    for (; p.bC < p.aD.length; p.eff == 102 && kJ(p), p.bC++) {
        if (bH < p.aD[p.bC + 1]) {
            break;
        }
    }
    bH -= p.aD[p.bC];
    if (p.eff == 101) {
        cR(bH);
    } else {
        ks(bH);
    }
}
function mJ(aN, p) {
    var au = p.aU;
    p.bf.beginPath();
    for (var i = 0; i < p.aU.length; i++) {
        if (au[i][0] == "C" || au[i][0] == "c") {
            var aT = au[i][3];
            p.bf.bezierCurveTo(p.aq + aT.bq.x, p.by + aT.bq.y, p.aq + aT.ax.x, p.by + aT.ax.y, p.aq + aT.bQ.x, p.by + aT.bQ.y);
        } else if (au[i][0] == "L") {
            p.bf.lineTo(p.aq + au[i][1], p.by + au[i][2]);
        } else if (au[i][0] == "M") {
            p.bf.moveTo(p.aq + au[i][1], p.by + au[i][2]);
        }
    }
    p.bf.stroke();
    if (p.fill) {
        p.bf.fill();
    }
};
var eF = {
    e14: function(p) {
        p.fk = [];
        var ai = p.dir == 16 ? p.bp.height: p.bp.width;
        for (var i = 0; i < ai; i++) {
            p.fk.push(i);
        }
    },
    e18: function(p) {
        p.rows = parseInt(p.bp.height / 16);
    }
}
function kw(bf, p, r) {
    bf.beginPath();
    if ((p.dir + p.exit) != 20) {
        r = 1 - r;
        p.bf.globalCompositeOperation = "destination-out";
        bf.fillRect(p.cx - p.bp.width / 2 * r, p.cy - p.bp.height / 2 * r, p.bp.width * r, p.bp.height * r);
    } else {
        bf.fillRect(p.cx - p.bp.width / 2 * r, p.cy - p.bp.height / 2 * r, p.bp.width * r, p.bp.height * r);
    }
}
function kI(bf, p, r) {
    bf.beginPath();
    if (p.dir == 16) {
        var ab = Math.ceil(p.bp.width / 6);
        var bL = Math.ceil(p.bp.height / 12);
        for (var v = 0; v < 12; v++) {
            var dx = v & 1 ? ab / 2 : 0;
            for (var i = 0; i < 7; i++) {
                bf.fillRect(i * ab - dx, v * bL, ab * r, bL);
            }
        }
    } else {
        var ab = Math.ceil(p.bp.width / 12);
        var bL = Math.ceil(p.bp.height / 6);
        for (var v = 0; v < 12; v++) {
            var dy = v & 1 ? bL / 2 : 0;
            for (var i = 0; i < 7; i++) {
                bf.fillRect(v * ab, i * bL - dy, ab, bL * r);
            }
        }
    }
}
function Circle(bf, p, r) {
    bf.beginPath();
    p.bf.globalCompositeOperation = (p.dir - p.exit) == 19 ? "destination-in": "destination-out";
    bf.arc(p.cx, p.cy, p.radius * r, 0, 2 * Math.PI);
    bf.fill();
}
function hU(bf, p, r) {
    bf.beginPath();
    if (p.dir && p.dir == 19) {
        r = 1 - r;
        bf.moveTo(p.cx - 1.1 * r * p.bp.width, p.cy);
        bf.lineTo(p.cx, p.cy - 1.1 * r * p.bp.height);
        bf.lineTo(p.cx + 1.1 * r * p.bp.width, p.cy);
        bf.lineTo(p.cx, p.cy + 1.1 * r * p.bp.height);
        bf.lineTo(p.cx - 1.1 * r * p.bp.width, p.cy);
        p.bf.globalCompositeOperation = "destination-out";
    } else {
        bf.moveTo(p.cx - 1.1 * r * p.bp.width, p.cy);
        bf.lineTo(p.cx, p.cy - 1.1 * r * p.bp.height);
        bf.lineTo(p.cx + 1.1 * r * p.bp.width, p.cy);
        bf.lineTo(p.cx, p.cy + 1.1 * r * p.bp.height);
        bf.lineTo(p.cx - 1.1 * r * p.bp.width, p.cy);
    }
    bf.fill();
}
function nv(bf, p, r) {
    bf.beginPath();
    p.bf.globalCompositeOperation = "destination-out";
    r = 1 - r;
    bf.fillRect(0, 0, p.bp.width * (r / 2), p.bp.height * (r / 2));
    bf.fillRect(p.bp.width, 0, -p.bp.width * (r / 2), p.bp.height * (r / 2));
    bf.fillRect(0, p.bp.height, p.bp.width * (r / 2), -p.bp.height * (r / 2));
    bf.fillRect(p.bp.width, p.bp.height, -p.bp.width * (r / 2), -p.bp.height * (r / 2));
}
function kd(bf, p, r) {
    bf = p.bf;
    bf.drawImage(p.o.bI, 0, 0);
    switch (p.dir) {
    case 23:
        bf.clearRect(0, p.bp.height * 0.5, p.bp.width, p.bp.height * (0.5 - r / 2));
        bf.clearRect(0, p.bp.height * 0.5, p.bp.width, -p.bp.height * (0.5 - r / 2));
        break;
    case 24:
        bf.clearRect(0, 0, p.bp.width, p.bp.height * (0.5 - r / 2));
        bf.clearRect(0, p.bp.height, p.bp.width, -p.bp.height * (0.5 - r / 2));
        break;
    case 25:
        bf.clearRect(p.bp.width * 0.5, 0, p.bp.width * (0.5 - r / 2), p.bp.height);
        bf.clearRect(p.bp.width * 0.5, 0, -p.bp.width * (0.5 - r / 2), p.bp.height);
        break;
    case 26:
        bf.clearRect(0, 0, p.bp.width * (0.5 - r / 2), p.bp.height);
        bf.clearRect(p.bp.width, 0, -p.bp.width * (0.5 - r / 2), p.bp.height);
        break;
    default:
        break;
    }
    bf.canvas.style[hx] = bf.bJ;
    p.fJ = 1;
}
function kj(bf, p, r) {
    bf.beginPath();
    var dir = (p.dir + (p.exit << 1)) & 3;
    switch (dir) {
    case 3:
        p.bf.drawImage(p.o.bI, 0, 0);
        p.bf.clearRect(0, 0, p.bp.width, p.bp.height * (1 - r));
        p.fJ = 1;
        break;
    case 0:
        bf.fillRect(0, 0, p.bp.width * (r), p.bp.height);
        break;
    case 2:
        p.bf.drawImage(p.o.bI, 0, 0);
        p.bf.clearRect(0, 0, p.bp.width * (1 - r), p.bp.height);
        p.fJ = 1;
        break;
    case 1:
        bf.fillRect(0, 0, p.bp.width, p.bp.height * (r));
        break;
    }
}
function lP(bf, p, r) {
    var bf = p.bf;
    p.fJ = 1;
    if (p.dir == 0) {
        var bK = p.bp.height / 12;
        var px = p.bp.width * (r - 1);
        for (var i = 0; i < 6; i++) {
            bf.drawImage(p.o.bI, px, i * bK * 2, p.bp.width, bK, 0, i * bK * 2, p.bp.width, bK);
            bf.drawImage(p.o.bI, -px, i * bK * 2 + bK, p.bp.width, bK, 0, i * bK * 2 + bK, p.bp.width, bK);
        }
    } else {
        var bK = p.bp.width / 12;
        var fE = p.bp.height * (r - 1);
        for (var i = 0; i < 6; i++) {
            bf.drawImage(p.o.bI, i * bK * 2, fE, bK, p.bp.height, i * bK * 2, 0, bK, p.bp.height);
            bf.drawImage(p.o.bI, i * bK * 2 + bK, -fE, bK, p.bp.height, i * bK * 2 + bK, 0, bK, p.bp.height);
        }
    }
}
function ic(bf, p, r) {
    p.dk.beginPath();
    p.dk.fillStyle = "green";
    if (p.dir == 16) {
        var ab = Math.ceil(p.bp.width / 6);
        for (var i = 0; i < 6; i++) {
            p.dk.fillRect(i * ab, 0, ab * r, p.bp.height);
        }
    } else {
        var bL = Math.ceil(p.bp.height / 6);
        for (var i = 0; i < 6; i++) {
            p.dk.fillRect(0, i * bL, p.bp.width, bL * r);
        }
    }
}
function kO(bf, p, r) {
    bf.beginPath();
    bf.arc(p.cx, p.cy, p.radius, (1.5 - r) * Math.PI, 1.5 * Math.PI);
    bf.arc(p.cx, p.cy, p.radius, 1.5 * Math.PI, (1.5 + r) * Math.PI);
    bf.lineTo(p.cx, p.cy);
    bf.closePath();
    bf.fill();
}
function Wheel(bf, p, r) {
    var b = 2 / p.amt;
    bf.beginPath();
    for (var i = 0; i < p.amt; i++) {
        bf.arc(p.cx, p.cy, p.radius, (1.5 + i * b) * Math.PI, (1.5 + i * b + r * b) * Math.PI);
        bf.lineTo(p.cx, p.cy);
    }
    bf.closePath();
    bf.fill();
}
function ml(aN, p, r) {
    bf = p.bf;
    bf.clearRect(0, 0, p.bp.width, p.bp.height);
    switch (p.dir) {
    case 1:
        bf.drawImage(p.o.bI, 0, p.bp.height * (r - 1));
        break;
    case 2:
        bf.drawImage(p.o.bI, p.bp.width * (1 - r), 0);
        break;
    case 3:
        bf.drawImage(p.o.bI, 0, p.bp.height * (1 - r));
        break;
    case 4:
        bf.drawImage(p.o.bI, p.bp.width * (r - 1), 0);
        break;
    }
};
var gh = {
    e3: ic,
    e4: kw,
    e5: kI,
    e6: Circle,
    e8: hU,
    e9: function(bf, p, r) {},
    e13: nv,
    e14: function(bf, p, r) {
        if (p.dir == 16) {
            var rest = p.fk.length - p.bp.height * r;
            for (var i = 0; i < rest; i++) {}
        } else {}
    },
    e16: kd,
    e18: function(bf, p, r) {
        bf = p.bf;
        bf.drawImage(p.o.bI, 0, 0);
        var bK = p.bp.width / 16;
        var cl = Math.ceil(p.bp.height / 5);
        var ab = (p.bp.width + 5 * bK);
        r = 1 - r;
        switch (p.dir) {
        case 6:
            for (var i = 0; i < 5; i++) {
                bf.clearRect(bK * (i + 5), cl * i, ab * r, cl);
            }
            break;
        case 7:
            for (var i = 0; i < 5; i++) {
                bf.clearRect(bK * (i - 5), cl * i, ab * r, cl);
            }
            break;
        case 8:
            for (var i = 0; i < 5; i++) {
                bf.clearRect((p.bp.width + (i + 5) * bK), cl * i, -ab * r, cl);
            }
            break;
            break;
        default:
            for (var i = 0; i < 5; i++) {
                bf.clearRect((p.bp.width + (5 - i) * bK), cl * i, -ab * r, cl);
            }
            break;
        }
        p.fJ = 1;
    },
    e20: kO,
    e21: Wheel,
    e22: kj
}
function af(aN, p) {
    if (!p.bp) {
        ed(aN, p);
        p.cx = p.bp.width / 2;
        p.cy = p.bp.height / 2 - 2;
        p.radius = p.bp.width > p.bp.height ? p.bp.width: p.bp.height;
        p.fB = gh["e" + p.eff];
        var fV = document.createElement("div");
		fV.className = "cvs";
        aN.parentNode.insertBefore(fV, aN);
        fV.appendChild(aN);
        fV.appendChild(p.bp);
        aN.style.visibility = "hidden";
        fV.style.left = aN.style.left;
        fV.style.top = aN.style.top;
        aN.style.left = aN.style.top = "";
    } else {
        aN.style.visibility = "hidden";
        p.bp.style.visibility = "";
    }
}
function ed(o, p) {
    var para = document.createElement("canvas");
    var fa = document.createElement("canvas");
    var T = o.style[hx];
    o.style[hx] = "";
    var bf = para.getContext("2d");
    para.style.position = "absolute";
    p.bp = para;
    p.hw = fa;
    p.bf = bf;
    p.dk = fa.getContext("2d");
    o.style[hx] = T;
    bf.bJ = T;
    if (!o.bI) {
        var fV = o.cloneNode(true);
        document.body.appendChild(fV);
        fV.style.visibility = "hidden";
        var rect = fV.getBoundingClientRect();
        fa.width = para.width = rect.width + 4;
        fa.height = para.height = rect.height + 4;
        var hq = document.createElement("canvas");
        bf = hq.getContext("2d");
        hq.width = para.width;
        hq.height = para.height;
        bf.aq = rect.left;
        bf.by = rect.top;
		var ndpos=fV.style.top;
		//fV.style.top = "2000px";
		fV.style.visibility = "";
        for (var i = 0, ai = o.children.length; i < ai; i++) {
            co(fV.children[i], bf);
        }		
		fV.style.visibility = "hidden";
		fV.style.top = ndpos;
        o.bI = hq;
        document.body.removeChild(fV);
    }
}
function co(ah, bf) {
    if (ah.style.display == "none" || ah.style.visibility == "hidden") {
        return;
    }
    if (ah.tagName == "IMG") {
        var rect = ah.getBoundingClientRect();
        bf.drawImage(ah, (rect.left - bf.aq), (rect.top - bf.by), rect.width, rect.height);
    } else if (ah.tagName.toLowerCase() == "svg") {
        var img = new Image();
        var xml = new XMLSerializer().serializeToString(ah);
        var iG = document.createElement("canvas");
        canvg(iG, xml);
        bf.drawImage(iG, -2, -2);
    } else if ((ah.tagName == "SPAN" || ah.tagName == "A") && ah.children.length == 0) {
        var bu = window.getComputedStyle(ah, null);
        var cB = "";
        cB = ah.style["font-style"] + " " + ah.style["font-variant"] + " " + ah.style["font-weight"];
        cB += " " + bu.getPropertyValue("font-size");
        cB += " " + bu.getPropertyValue("font-family");
        bf.font = cB;
        bf.fillStyle = bu.getPropertyValue("color");
        var rect = ah.getBoundingClientRect();
        bf.textBaseline = "middle";
        if (window.isEdge) {
            bf.fillText(ah.innerText, (rect.left - bf.aq) / window.ew, (rect.top - bf.by + (rect.height / 2)) / window.ew + 1);
        } else {
            bf.fillText(ah.innerText, (rect.left - bf.aq), (rect.top - bf.by + (rect.height / 2)) + 1);
        }
    } else {
        for (var i = 0,
        ai = ah.children.length; i < ai; i++) {
            co(ah.children[i], bf);
        }
    }
}
function gG(ht) {
    var bM = window.document.getElementById(ht);
    var T = bM.style[hx];
    bM.style[hx] = "";
    var rect = bM.getBoundingClientRect();
    aq = rect.left;
    by = rect.top;
    var gX = window.document.getElementById("ccc");
    var du = gX.getContext("2d");
    du.clearRect(0, 0, gX.width, gX.height);
    du.fillStyle = "#FF0000";
    for (var i = 0,
    ai = bM.children.length; i < ai; i++) {
        co(bM.children[i], du);
    }
    bM.style[hx] = T;
};
var eC = {
    e1: function(aN, p) {},
    e2: function(aN, p) {
        if (p.sx == undefined) {
            var mN, ho, aZ, ao, aF, cS, bD, cI;
            var hi = parseFloat(aN.style.left);
            var gS = parseFloat(aN.style.top);
            if (isNaN(hi)) hi = 0;
            if (isNaN(gS)) gS = 0;
            p.dx = hi;
            p.dy = gS;
            bD = aN.scrollWidth;
            nm = aN.scrollHeight;
            aF = hi - aS;
            ao = gS + aP;
            aZ = hi + aS;
            switch (p.dir) {
            case 3:
                p.sx = p.dx = hi;
                p.sy = aZ + 10;
                break;
            case 9:
                p.sx = aF - bD - 10;
                p.sy = ao + 10;
                break;
            case 8:
                p.sx = aZ + 10;
                p.sy = ao + 10;
                break;
            case 4:
                p.sx = aF - bD - 10;
                p.sy = p.dy;
                break;
            case 2:
                p.sx = aZ + 10;
                p.sy = p.dy;
                break;
            case 1:
                p.sx = p.dx;
                p.sy = p.dy - aP;
                break;
            case 6:
                p.sx = aF - bD - 10;
                p.sy = p.dy - aP;
                break;
            case 7:
                p.sx = aZ + 10;
                p.sy = p.dy - aP;
                break;
            default:
                p.fb.fb == "ttt";
                break;
            }
        }
        aN.style.left = p.exit ? p.dx + "px": p.sx + "px";
        aN.style.top = p.exit ? p.dy + "px": p.sy + "px";
        p.reset = ereset["e" + p.eff];
    },
    e3: window.af,
    e4: window.af,
    e5: window.af,
    e6: window.af,
    e7: kf,
    e8: window.af,
    e9: window.af,
    e10: ji,
    e11: iL,
    e12: window.af,
    e13: window.af,
    e14: function(aN, p) {
        window.af(aN, p);
        window.eF.e14(p);
    },
    e15: lo,
    e16: window.af,
    e17: jU,
    e18: function(aN, p) {
        window.af(aN, p);
        window.eF.e18(p);
    },
    e19: gY,
    e20: window.af,
    e21: window.af,
    e22: window.af,
    e23: kC,
    e25: iI,
    e26: jR,
    e28: kH,
    e30: jZ,
    e31: lM,
    e32: lY,
    e33: mP,
    e34: lE,
    e35: jI,
    e36: lv,
    e37: lf,
    e38: ki,
    e39: ib,
    e40: jS,
    e41: gY,
    e42: ib,
    e43: lH,
    e44: lu,
    e45: jP,
    e46: ng,
    e47: kS,
    e48: ju,
    e49: iZ,
    e50: iM,
    e51: lc,
    e53: le,
    e54: ky,
    e55: kt,
    e56: il,
    e57: kT,
    e58: kX,
    e59: mC,
    e60: kY,
    e61: kW,
    e62: lj,
    e63: jp,
    e64: mf,
    e65: jD,
    e66: jQ,
    e67: ly,
    e68: il,
    e69: jJ,
    e70: kn,
    e71: lA,
    e72: lB,
    e73: ir,
    e74: jl,
    e75: iA,
    e76: iT,
    e77: mo,
    e78: kV,
    e79: jK,
    e80: kD,
    e81: jO,
    e82: kx,
    e86: nb,
    e87: jj,
    e101: window.fN,
    e102: window.fN
}
function gL(aN, p, r) {
    var bf = p.bf;
    if (bf == undefined) {
        return;
    }
    bf.restore();
    bf.save();
    p.dk.clearRect(0, 0, p.hw.width, p.hw.height);
    p.dk.fillStyle = "green";
    if (r > 0.3 && r < 0.8) {}
    if (p.fB) {
        bf.clearRect(0, 0, p.bp.width, p.bp.height);
        bf.drawImage(p.o.bI, 0, 0);
        bf.globalCompositeOperation = "destination-in";
        p.fB(p.dk, p, r);
        if (p.fJ == undefined) {
            bf.drawImage(p.hw, 0, 0)
        }
    }
};
var ereset = {
    e2: function(aN, p) {
        aN.style.left = p.exit ? p.dx + "px": p.sx + "px";
        aN.style.top = p.exit ? p.dy + "px": p.sy + "px";
    },
    e17: lF,
    e32: kA,
    e33: mG,
    e41: kk,
    e43: lG,
    e44: kq,
    e45: lb,
    e46: mr,
    e47: hV,
    e48: je,
    e49: ll,
    e50: jx,
    e51: jh,
    e53: kc,
    e54: ko,
    e55: aI,
    e56: aI,
    e57: aI,
    e58: aI,
    e59: mK,
    e60: kR,
    e61: aI,
    e62: kP,
    e64: aI,
    e65: aI,
    e66: aI,
    e67: aI,
    e68: aI,
    e69: aI,
    e70: ld,
    e71: jN,
    e72: aI,
    e73: aI,
    e74: aI,
    e76: aI,
    e77: aI,
    e78: aI,
    e79: aI,
    e81: aI,
    e86: aI,
    e101: function(aN, p) {
		if(aN.parentNode.className == "cvs"){
			p.o=aN=aN.parentNode;
		}
		if(p.aq){
			aN.style.left = p.aq + "px";
			aN.style.top = p.by + "px";
		}
    },
    e102: function(aN, p) {
        p.bf.clearRect(0, 0, aS, aP);
    }
};
var ay = {
    e1: function(aN, p, r) {
        if (r != 0 && r != 1) {
            r = 1 - p.exit;
        }
        aN.style.visibility = (r == 1) ? "": "hidden";
    },
    e2: function(aN, p, r) {
        aN.style.left = ((p.dx - p.sx) * r + p.sx) + "px";
        aN.style.top = ((p.dy - p.sy) * r + p.sy) + "px";
    },
    e3: gL,
    e4: gL,
    e5: gL,
    e6: gL,
    e7: bS,
    e8: gL,
    e9: gL,
    e10: bS,
    e11: jo,
    e12: ml,
    e13: gL,
    e14: gL,
    e15: kK,
    e16: gL,
    e17: bS,
    e18: gL,
    e19: hY,
    e20: gL,
    e21: gL,
    e22: gL,
    e23: bS,
    e25: iE,
    e26: lg,
    e28: bS,
    e28: bS,
    e30: jv,
    e31: bS,
    e32: nf,
    e33: bS,
    e34: ns,
    e35: kv,
    e36: jY,
    e37: jL,
    e38: kE,
    e39: bS,
    e40: kL,
    e41: hY,
    e42: bS,
    e43: lU,
    e44: bS,
    e45: bS,
    e46: kb,
    e47: lk,
    e48: bS,
    e49: bS,
    e50: bS,
    e51: jf,
    e53: bS,
    e54: ln,
    e55: jT,
    e56: hZ,
    e57: lq,
    e58: kz,
    e59: lZ,
    e60: jX,
    e61: mH,
    e62: km,
    e63: js,
    e64: mw,
    e65: la,
    e66: ke,
    e67: jM,
    e68: hZ,
    e69: kG,
    e70: kl,
    e71: ku,
    e72: kU,
    e73: eO,
    e74: eO,
    e75: hT,
    e76: jg,
    e77: mX,
    e78: eO,
    e79: lx,
    e80: kQ,
    e81: kh,
    e82: kp,
    e86: nh,
    e87: ig,
    e101: window.fG,
    e102: window.fG
};