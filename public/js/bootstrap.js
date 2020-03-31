// Native Javascript for Bootstrap 4 v2.0.15 | © dnp_theme | MIT-License
!function(t,e){if("function"==typeof define&&define.amd)define([],e);else if("object"==typeof module&&module.exports)module.exports=e();else{var n=e();t.Alert=n.Alert,t.Button=n.Button,t.Carousel=n.Carousel,t.Collapse=n.Collapse,t.Dropdown=n.Dropdown,t.Modal=n.Modal,t.Popover=n.Popover,t.ScrollSpy=n.ScrollSpy,t.Tab=n.Tab,t.Tooltip=n.Tooltip}}(this,function(){"use strict";var t="undefined"!=typeof global?global:this||window,e=document.documentElement,n=document.body,i="data-toggle",o="data-dismiss",l="data-spy",a="data-ride",r="Alert",c="Button",s="Carousel",u="Collapse",f="Dropdown",d="Modal",h="Popover",p="ScrollSpy",v="Tab",m="Tooltip",g="data-backdrop",b="data-keyboard",w="data-target",T="data-interval",y="data-height",x="data-pause",C="data-original-title",A="data-dismissible",E="data-trigger",L="data-animation",k="data-container",I="data-placement",D="data-delay",M="backdrop",S="keyboard",H="delay",N="content",B="target",P="interval",W="pause",$="animation",j="placement",O="container",q="offsetTop",R="offsetLeft",z="scrollTop",U="scrollLeft",X="clientWidth",Y="clientHeight",F="offsetWidth",G="offsetHeight",J="innerWidth",K="innerHeight",Q="scrollHeight",V="height",Z="aria-expanded",_="aria-hidden",tt="click",et="hover",nt="keydown",it="resize",ot="scroll",lt="show",at="shown",rt="hide",ct="hidden",st="close",ut="closed",ft="slid",dt="slide",ht="change",pt="getAttribute",vt="setAttribute",mt="hasAttribute",gt="getElementsByTagName",bt="getBoundingClientRect",wt="querySelectorAll",Tt="getElementsByClassName",yt="indexOf",xt="parentNode",Ct="length",At="toLowerCase",Et="Transition",Lt="Webkit",kt="style",It="active",Dt="show",Mt="collapsing",St="left",Ht="right",Nt="top",Bt="bottom",Pt="fixed-top",Wt="fixed-bottom",$t="onmouseleave"in document?["mouseenter","mouseleave"]:["mouseover","mouseout"],jt=/\b(top|bottom|left|top)+/,Ot=Lt+Et in e[kt]||Et[At]()in e[kt],qt=Lt+Et in e[kt]?Lt[At]()+Et+"End":Et[At]()+"end",Rt=function(t){t.focus?t.focus():t.setActive()},zt=function(t,e){t.classList.add(e)},Ut=function(t,e){t.classList.remove(e)},Xt=function(t,e){return t.classList.contains(e)},Yt=function(t,e){return[].slice.call(t[Tt](e))},Ft=function(t,e){var n=e?e:document;return"object"==typeof t?t:n.querySelector(t)},Gt=function(t,e){for(var n=e.charAt(0);t&&t!==document;t=t[xt])if("."===n){if(null!==Ft(e,t[xt])&&Xt(t,e.replace(".","")))return t}else if("#"===n&&t.id===e.substr(1))return t;return!1},Jt=function(t,e,n){t.addEventListener(e,n,!1)},Kt=function(t,e,n){t.removeEventListener(e,n,!1)},Qt=function(t,e,n){Jt(t,e,function i(o){n(o),Kt(t,e,i)})},Vt=function(t,e){Ot?Qt(t,qt,function(t){e(t)}):e()},Zt=function(t,e,n){var i=new CustomEvent(t+".bs."+e);i.relatedTarget=n,this.dispatchEvent(i)},_t=function(t,e,n){for(var i=0;i<n[Ct];i++)new e(n[i])},te=/^\#(.)+$/,ee=function(n){var i=n[bt]();return i[Nt]>=0&&i[St]>=0&&i[Bt]<=(t[K]||e[Y])&&i[Ht]<=(t[J]||e[X])},ne=function(){return{y:t.pageYOffset||e[z],x:t.pageXOffset||e[U]}},ie=function(t,e,i,o){var l=t[bt](),a=Ft(".arrow",e),r=a[F],c=Xt(e,"popover"),s=o===n?ne():{x:o[R]+o[U],y:o[q]+o[z]},u={w:l[Ht]-l[St],h:l[Bt]-l[Nt]},f={w:e[F],h:e[G]};i===Nt?(e[kt][Nt]=l[Nt]+s.y-f.h-(c?r:0)+"px",e[kt][St]=l[St]+s.x-f.w/2+u.w/2+"px",a[kt][St]=f.w/2-r/2+"px"):i===Bt?(e[kt][Nt]=l[Nt]+s.y+u.h+"px",e[kt][St]=l[St]+s.x-f.w/2+u.w/2+"px",a[kt][St]=f.w/2-r/2+"px"):i===St?(e[kt][Nt]=l[Nt]+s.y-f.h/2+u.h/2+"px",e[kt][St]=l[St]+s.x-f.w-(c?r:0)+"px",a[kt][Nt]=f.h/2-r/2+"px"):i===Ht&&(e[kt][Nt]=l[Nt]+s.y-f.h/2+u.h/2+"px",e[kt][St]=l[St]+s.x+u.w+"px",a[kt][Nt]=f.h/2-r/2+"px"),e.className[yt](i)===-1&&(e.className=e.className.replace(jt,i))},oe=function(t){return t===Nt?Bt:t===Bt?Nt:t===St?Ht:t===Ht?St:t},le=function(t){t=Ft(t);var e=this,n="alert",i=Gt(t,"."+n),l=function(){Xt(i,"fade")?Vt(i,c):c()},a=function(l){var a=l[B];a=a[mt](o)?a:a[xt],a&&a[mt](o)&&(i=Gt(a,"."+n),t=Ft("["+o+'="'+n+'"]',i),(t===a||t===a[xt])&&i&&e.close())},c=function(){Zt.call(i,ut,n),Kt(t,tt,a),i[xt].removeChild(i)};this.close=function(){i&&t&&Xt(i,Dt)&&(Zt.call(i,st,n),Ut(i,Dt),i&&l())},r in t||Jt(t,tt,a),t[r]=this};_t(r,le,e[wt]("["+o+'="alert"]'));var ae=function(t){t=Ft(t);var e=!1,n="button",i="checked",o="LABEL",l="INPUT",a=function(a){var r=a[B][xt],c=a[B].tagName===o?a[B]:r.tagName===o?r:null;if(c){var s=this,u=Yt(s,"btn"),f=c[gt](l)[0];if(f){if("checkbox"===f.type&&(f[i]?(Ut(c,It),f[pt](i),f.removeAttribute(i),f[i]=!1):(zt(c,It),f[pt](i),f[vt](i,i),f[i]=!0),e||(e=!0,Zt.call(f,ht,n),Zt.call(t,ht,n))),"radio"===f.type&&!e&&!f[i]){zt(c,It),f[vt](i,i),f[i]=!0,Zt.call(f,ht,n),Zt.call(t,ht,n),e=!0;for(var d=0,h=u[Ct];d<h;d++){var p=u[d],v=p[gt](l)[0];p!==c&&Xt(p,It)&&(Ut(p,It),v.removeAttribute(i),v[i]=!1,Zt.call(v,ht,n))}}setTimeout(function(){e=!1},50)}}};Xt(t,"btn-group")&&(c in t||Jt(t,tt,a),t[c]=this)};_t(c,ae,e[wt]("["+i+'="buttons"]'));var re=function(e,n){e=Ft(e),n=n||{};var i="false"!==e[pt](T)&&(parseInt(e[pt](T))||5e3),o=e[pt](x)===et||!1,l="true"===e[pt](b)||!1,a="carousel",r="paused",c="direction",u="carousel-item",f="data-slide-to";this[S]=n[S]===!0||l,this[W]=!(n[W]!==et&&!o)&&et,n[P]||i?this[P]=parseInt(n[P])||i:this[P]=!1;var d=this,h=e.index=0,p=e.timer=0,v=!1,m=Yt(e,u),g=m[Ct],w=this[c]=St,y=Yt(e,a+"-control-prev")[0],C=Yt(e,a+"-control-next")[0],A=Ft("."+a+"-indicators",e),E=A&&A[gt]("LI")||[],L=function(){d[P]===!1||Xt(e,r)||(zt(e,r),!v&&clearInterval(p))},k=function(){d[P]!==!1&&Xt(e,r)&&(Ut(e,r),!v&&clearInterval(p),!v&&d.cycle())},I=function(t){if(t.preventDefault(),!v){var e=t[B],n=d.getActiveIndex();if(!e||Xt(e,It)||!e[pt](f))return!1;h=parseInt(e[pt](f),10),n<h||0===n&&h===g-1?w=d[c]=St:(n>h||n===g-1&&0===h)&&(w=d[c]=Ht),d.slideTo(h)}},D=function(t){if(t.preventDefault(),!v){var e=t.currentTarget||t.srcElement;e===C?(h++,w=d[c]=St,h===g-1?h=g-1:h===g&&(h=0)):e===y&&(h--,w=d[c]=Ht,0===h?h=0:h<0&&(h=g-1)),d.slideTo(h)}},M=function(t){if(!v){switch(t.which){case 39:h++,w=d[c]=St,h==g-1?h=g-1:h==g&&(h=0);break;case 37:h--,w=d[c]=Ht,0==h?h=0:h<0&&(h=g-1);break;default:return}d.slideTo(h)}},H=function(t){for(var e=0,n=E[Ct];e<n;e++)Ut(E[e],It);E[t]&&zt(E[t],It)};this.cycle=function(){w=this[c]=St,p=setInterval(function(){h++,h=h===g?0:h,d.slideTo(h)},this[P])},this.slideTo=function(t){var n=this.getActiveIndex(),i=w===St?"next":"prev";Zt.call(e,dt,a,m[t]),v=!0,clearInterval(p),H(t),Ot&&Xt(e,"slide")?(zt(m[t],u+"-"+i),m[t][F],zt(m[t],u+"-"+w),zt(m[n],u+"-"+w),Qt(m[n],qt,function(o){var l=o[B]!==m[n]?1e3*o.elapsedTime:0;setTimeout(function(){v=!1,zt(m[t],It),Ut(m[n],It),Ut(m[t],u+"-"+i),Ut(m[t],u+"-"+w),Ut(m[n],u+"-"+w),Zt.call(e,ft,a,m[t]),document.hidden||!d[P]||Xt(e,r)||d.cycle()},l+100)})):(zt(m[t],It),m[t][F],Ut(m[n],It),setTimeout(function(){v=!1,d[P]&&!Xt(e,r)&&d.cycle(),Zt.call(e,ft,a,m[t])},100))},this.getActiveIndex=function(){return m[yt](Yt(e,u+" active")[0])||0},s in e||(this[W]&&this[P]&&(Jt(e,$t[0],L),Jt(e,$t[1],k),Jt(e,"touchstart",L),Jt(e,"touchend",k)),C&&Jt(C,tt,D),y&&Jt(y,tt,D),A&&Jt(A,tt,I,!1),this[S]===!0&&Jt(t,nt,M,!1)),this.getActiveIndex()<0&&(m[Ct]&&zt(m[0],It),E[Ct]&&H(0)),this[P]&&this.cycle(),e[s]=this};_t(s,re,e[wt]("["+a+'="carousel"]'));var ce=function(t,e){t=Ft(t),e=e||{};var n=null,o=null,l=this,a=!1,r=t[pt]("data-parent"),c="collapse",s="collapsed",f=function(t){Zt.call(t,lt,c),a=!0,zt(t,Mt),Ut(t,c),t[kt][V]=t[Q]+"px",Vt(t,function(){a=!1,t[vt](Z,"true"),Ut(t,Mt),zt(t,c),zt(t,Dt),t[kt][V]="",Zt.call(t,at,c)})},d=function(t){Zt.call(t,rt,c),a=!0,t[kt][V]=t[Q]+"px",Ut(t,c),zt(t,Mt),t[F],t[kt][V]="0px",Vt(t,function(){a=!1,t[vt](Z,"false"),Ut(t,Mt),zt(t,c),Ut(t,Dt),t[kt][V]="",Zt.call(t,ct,c)})},h=function(){var e=t.href&&t[pt]("href"),n=t[pt](w),i=e||n&&te.test(n)&&n;return i&&Ft(i)};this.toggle=function(t){t.preventDefault(),a||(Xt(o,Dt)?l.hide():l.show())},this.hide=function(){d(o),zt(t,s)},this.show=function(){if(n){var e=Ft("."+c+"."+Dt,n),l=e&&(Ft("["+i+'="'+c+'"]['+w+'="#'+e.id+'"]',n)||Ft("["+i+'="'+c+'"][href="#'+e.id+'"]',n)),a=l&&(l[pt](w)||l.href);e&&l&&e!==o&&(d(e),a.split("#")[1]!==o.id?zt(l,s):Ut(l,s))}f(o),Ut(t,s)},u in t||Jt(t,tt,this.toggle),o=h(),n=Ft(e.parent)||r&&Gt(t,r),t[u]=this};_t(u,ce,e[wt]("["+i+'="collapse"]'));var se=function(t,e){t=Ft(t),this.persist=e===!0||"true"===t[pt]("data-persist")||!1;var n=this,o=!1,l=t[xt],a="dropdown",r=null,c=Ft(".dropdown-menu",l),s=c[gt]("*"),u=function(t){!o||27!=t.which&&27!=t.keyCode||(r=null,p())},d=function(e){var a=e[B],u=a&&(a[pt](i)||a[xt]&&pt in a[xt]&&a[xt][pt](i));if(a===t||a===l||a[xt]===t)e.preventDefault(),r=t,n.toggle();else if(o){if((a===c||s&&[].slice.call(s)[yt](a)>-1)&&(n.persist||u))return;r=null,p()}(/\#$/.test(a.href)||a[xt]&&/\#$/.test(a[xt].href))&&e.preventDefault()},h=function(){Zt.call(l,lt,a,r),zt(c,Dt),zt(l,Dt),c[vt](Z,!0),Zt.call(l,at,a,r),Jt(document,nt,u),o=!0},p=function(){Zt.call(l,rt,a,r),Ut(c,Dt),Ut(l,Dt),c[vt](Z,!1),Zt.call(l,ct,a,r),Kt(document,nt,u),o=!1};this.toggle=function(){Xt(l,Dt)&&o?p():h()},f in t||(c[vt]("tabindex","0"),Jt(document,tt,d)),t[f]=this};_t(f,se,e[wt]("["+i+'="dropdown"]'));var ue=function(i,l){i=Ft(i);var a=i[pt](w)||i[pt]("href"),r=Ft(a),c=Xt(i,"modal")?i:r,s="modal",u="static",f="paddingLeft",h="paddingRight",p="modal-backdrop";if(Xt(i,"modal")&&(i=null),c){l=l||{},this[S]=l[S]!==!1&&"false"!==c[pt](b),this[M]=l[M]!==u&&c[pt](g)!==u||u,this[M]=l[M]!==!1&&"false"!==c[pt](g)&&this[M],this[N]=l[N];var v,m,T,y,x=this,C=this.open=!1,A=null,E=Yt(e,Pt).concat(Yt(e,Wt)),L=function(){var n=e[bt]();return t[J]||n[Ht]-Math.abs(n[St])},k=function(){var e,i=t.getComputedStyle(n),o=parseInt(i[h],10);if(v&&(n[kt][h]=o+T+"px",E[Ct]))for(var l=0;l<E[Ct];l++)e=t.getComputedStyle(E[l])[h],E[l][kt][h]=parseInt(e)+T+"px"},I=function(){if(n[kt][h]="",E[Ct])for(var t=0;t<E[Ct];t++)E[t][kt][h]=""},D=function(){var t,e=document.createElement("div");return e.className=s+"-scrollbar-measure",n.appendChild(e),t=e[F]-e[X],n.removeChild(e),t},H=function(){v=n[X]<L(),m=c[Q]>e[Y],T=D()},P=function(){c[kt][f]=!v&&m?T+"px":"",c[kt][h]=v&&!m?T+"px":""},W=function(){c[kt][f]="",c[kt][h]=""},$=function(){var t=document.createElement("div");y=Ft("."+p),null===y&&(t[vt]("class",p+" fade"),y=t,n.appendChild(y))},j=function(){y=Ft("."+p),y&&null!==y&&"object"==typeof y&&(n.removeChild(y),y=null)},O=function(){Xt(c,Dt)?Kt(document,nt,K):Jt(document,nt,K)},q=function(){Xt(c,Dt)?Kt(t,it,x.update):Jt(t,it,x.update)},R=function(){Xt(c,Dt)?Kt(c,tt,V):Jt(c,tt,V)},z=function(){C=x.open=!0,Rt(c),Zt.call(c,at,s,A)},U=function(){q(),R(),O(),c[kt].display="",C=x.open=!1,i&&Rt(i),Zt.call(c,ct,s),setTimeout(function(){Yt(document,s+" "+Dt)[0]||(W(),I(),Ut(n,s+"-open"),j())},100)},G=function(t){var e=t[B];e=e[mt](w)||e[mt]("href")?e:e[xt],C||e!==i||Xt(c,Dt)||(c.modalTrigger=i,A=i,x.show(),t.preventDefault())},K=function(t){var e=t.which||t.keyCode;x[S]&&27==e&&C&&x.hide()},V=function(t){var e=t[B];C&&(e[xt][pt](o)===s||e[pt](o)===s||e===c&&x[M]!==u)&&(x.hide(),A=null,t.preventDefault())};this.toggle=function(){C&&Xt(c,Dt)?this.hide():this.show()},this.show=function(){Zt.call(c,lt,s,A);var t=Yt(document,s+" "+Dt)[0];t&&t!==c&&t.modalTrigger[d].hide(),this[M]&&$(),y&&!Xt(y,Dt)&&setTimeout(function(){zt(y,Dt)},0),setTimeout(function(){c[kt].display="block",H(),k(),P(),q(),R(),O(),zt(n,s+"-open"),zt(c,Dt),c[vt](_,!1),Xt(c,"fade")?Vt(c,z):z()},Ot?150:0)},this.hide=function(){Zt.call(c,rt,s),y=Ft("."+p),Ut(c,Dt),c[vt](_,!0),!!y&&Ut(y,Dt),setTimeout(function(){Xt(c,"fade")?Vt(c,U):U()},Ot?150:0)},this.setContent=function(t){Ft("."+s+"-content",c).innerHTML=t},this.update=function(){C&&(H(),k(),P())},!i||d in i||Jt(i,tt,G),this[N]&&this.setContent(this[N]),!!i&&(i[d]=this)}};_t(d,ue,e[wt]("["+i+'="modal"]'));var fe=function(e,i){e=Ft(e);var o=e[pt](E),l=e[pt](L),a=e[pt](I),r=e[pt](A),c=e[pt](D),s=e[pt](k),u="popover",f="template",d="trigger",p="class",v="div",m="fade",g="data-title",b="data-content",w="dismissible",T='<button type="button" class="close">×</button>',y=Gt(e,".modal"),x=Gt(e,"."+Pt),C=Gt(e,"."+Wt);i=i||{},this[f]=i[f]?i[f]:null,this[d]=i[d]?i[d]:o||et,this[$]=i[$]&&i[$]!==m?i[$]:l||m,this[j]=i[j]?i[j]:a||Nt,this[H]=parseInt(i[H]||c)||200,this[w]=!(!i[w]&&"true"!==r),this[O]=Ft(i[O])?Ft(i[O]):Ft(s)?Ft(s):x?x:C?C:y?y:n;var M=this,S=e[pt](g)||null,N=e[pt](b)||null;if(N||this[f]){var P=null,W=0,q=this[j],R=function(t){null!==P&&t[B]===Ft(".close",P)&&M.hide()},z=function(){M[O].removeChild(P),W=null,P=null},U=function(){S=e[pt](g),N=e[pt](b),P=document.createElement(v);var t=document.createElement(v);if(t[vt](p,"arrow"),P.appendChild(t),null!==N&&null===M[f]){if(P[vt]("role","tooltip"),null!==S){var n=document.createElement("h3");n[vt](p,u+"-header"),n.innerHTML=M[w]?S+T:S,P.appendChild(n)}var i=document.createElement(v);i[vt](p,u+"-body"),i.innerHTML=M[w]&&null===S?N+T:N,P.appendChild(i)}else{var o=document.createElement(v);o.innerHTML=M[f],P.innerHTML=o.firstChild.innerHTML}M[O].appendChild(P),P[kt].display="block",P[vt](p,u+" bs-"+u+"-"+q+" "+M[$])},X=function(){!Xt(P,Dt)&&zt(P,Dt)},Y=function(){ie(e,P,q,M[O]),ee(P)||(q=oe(q),ie(e,P,q,M[O]))},F=function(){Zt.call(e,at,u)},G=function(){z(),Zt.call(e,ct,u)};this.toggle=function(){null===P?M.show():M.hide()},this.show=function(){clearTimeout(W),W=setTimeout(function(){null===P&&(q=M[j],U(),Y(),X(),Zt.call(e,lt,u),M[$]?Vt(P,F):F())},20)},this.hide=function(){clearTimeout(W),W=setTimeout(function(){P&&null!==P&&Xt(P,Dt)&&(Zt.call(e,rt,u),Ut(P,Dt),M[$]?Vt(P,G):G())},M[H])},h in e||(M[d]===et?(Jt(e,$t[0],M.show),M[w]||Jt(e,$t[1],M.hide)):/^(click|focus)$/.test(M[d])&&(Jt(e,M[d],M.toggle),M[w]||Jt(e,"blur",M.hide)),M[w]&&Jt(document,tt,R),Jt(t,it,M.hide)),e[h]=M}};_t(h,fe,e[wt]("["+i+'="popover"]'));var de=function(e,n){e=Ft(e);var i=Ft(e[pt](w));if(n=n||{},n[B]||i){for(var o,l=n[B]&&Ft(n[B])||i,a=l&&l[gt]("A"),r=[],c=[],s=e[G]<e[Q]?e:t,u=s===t,f=0,d=a[Ct];f<d;f++){var h=a[f][pt]("href"),v=h&&te.test(h)&&Ft(h);v&&(r.push(a[f]),c.push(v))}var m=function(t){var n=r[t],i=c[t],l=n[xt][xt],a=Xt(l,"dropdown")&&l[gt]("A")[0],s=u&&i[bt](),f=Xt(n,It)||!1,d=u?s[Nt]+o:i[q]-(c[t-1]?0:10),h=u?s[Bt]+o:c[t+1]?c[t+1][q]:e[Q],p=o>=d&&h>o;if(!f&&p)Xt(n,It)||(zt(n,It),f=!0,a&&!Xt(a,It)&&zt(a,It),Zt.call(e,"activate","scrollspy",r[t]));else if(p){if(!p&&!f||f&&p)return}else Xt(n,It)&&(Ut(n,It),f=!1,a&&Xt(a,It)&&!Yt(n[xt],It).length&&Ut(a,It))},g=function(){o=u?ne().y:e[z];for(var t=0,n=r[Ct];t<n;t++)m(t)};this.refresh=function(){g()},p in e||(Jt(s,ot,this.refresh),Jt(t,it,this.refresh)),this.refresh(),e[p]=this}};_t(p,de,e[wt]("["+l+'="scroll"]'));var he=function(t,e){t=Ft(t);var n=t[pt](y),o="tab",l="height",a="float",r="isAnimating";e=e||{},this[l]=!!Ot&&(e[l]||"true"===n);var c,s,u,f,d,h,p,m=this,g=Gt(t,".nav"),b=!1,w=g&&Ft(".dropdown-toggle",g),T=function(){b[kt][l]="",Ut(b,Mt),g[r]=!1},x=function(){b?h?T():setTimeout(function(){b[kt][l]=p+"px",b[F],Vt(b,T)},1):g[r]=!1,Zt.call(c,at,o,s)},C=function(){b&&(u[kt][a]="left",f[kt][a]="left",d=u[Q]),zt(f,It),Zt.call(c,lt,o,s),Ut(u,It),Zt.call(s,ct,o,c),b&&(p=f[Q],h=p===d,zt(b,Mt),b[kt][l]=d+"px",b[G],u[kt][a]="",f[kt][a]=""),Xt(f,"fade")?(zt(f,Dt),Vt(f,x)):x()};if(g){g[r]=!1;var A=function(){var t,e=Yt(g,It);return 1!==e[Ct]||Xt(e[0][xt],"dropdown")?e[Ct]>1&&(t=e[e[Ct]-1]):t=e[0],t},E=function(){return Ft(A()[pt]("href"))},L=function(t){t.preventDefault(),c=t[B][pt](i)===o||te.test(t[B][pt]("href"))?t[B]:t[B][xt],!g[r]&&!Xt(c,It)&&m.show()};this.show=function(){c=c||t,f=Ft(c[pt]("href")),s=A(),u=E(),g[r]=!0,Ut(s,It),zt(c,It),w&&(Xt(t[xt],"dropdown-menu")?Xt(w,It)||zt(w,It):Xt(w,It)&&Ut(w,It)),Zt.call(s,rt,o,c),Xt(u,"fade")?(Ut(u,Dt),Vt(u,C)):C()},v in t||Jt(t,tt,L),this[l]&&(b=E()[xt]),t[v]=this}};_t(v,he,e[wt]("["+i+'="tab"]'));var pe=function(t,e){t=Ft(t);var i=t[pt](L),o=t[pt](I),l=t[pt](D),a=t[pt](k),r="tooltip",c="class",s="title",u="fade",f="div",d=Gt(t,".modal"),h=Gt(t,"."+Pt),p=Gt(t,"."+Wt);e=e||{},this[$]=e[$]&&e[$]!==u?e[$]:i||u,this[j]=e[j]?e[j]:o||Nt,this[H]=parseInt(e[H]||l)||200,this[O]=Ft(e[O])?Ft(e[O]):Ft(a)?Ft(a):h?h:p?p:d?d:n;var v=this,g=0,b=this[j],w=null,T=t[pt](s)||t[pt](C);if(T){var y=function(){v[O].removeChild(w),w=null,g=null},x=function(){T=t[pt](s)||t[pt](C),w=document.createElement(f),w[vt]("role",r);var e=document.createElement(f);e[vt](c,"arrow"),w.appendChild(e);var n=document.createElement(f);n[vt](c,r+"-inner"),w.appendChild(n),n.innerHTML=T,v[O].appendChild(w),w[vt](c,r+" bs-"+r+"-"+b+" "+v[$])},A=function(){ie(t,w,b,v[O]),ee(w)||(b=oe(b),ie(t,w,b,v[O]))},E=function(){!Xt(w,Dt)&&zt(w,Dt)},M=function(){Zt.call(t,at,r)},S=function(){y(),Zt.call(t,ct,r)};this.show=function(){clearTimeout(g),g=setTimeout(function(){null===w&&(b=v[j],x(),A(),E(),Zt.call(t,lt,r),v[$]?Vt(w,M):M())},20)},this.hide=function(){clearTimeout(g),g=setTimeout(function(){w&&null!==w&&Xt(w,Dt)&&(Zt.call(t,rt,r),Ut(w,Dt),v[$]?Vt(w,S):S())},v[H])},this.toggle=function(){w?v.hide():v.show()},m in t||(t[vt](C,T),t.removeAttribute(s),Jt(t,$t[0],this.show),Jt(t,$t[1],this.hide)),t[m]=this}};return _t(m,pe,e[wt]("["+i+'="tooltip"]')),{Alert:le,Button:ae,Carousel:re,Collapse:ce,Dropdown:se,Modal:ue,Popover:fe,ScrollSpy:de,Tab:he,Tooltip:pe}});
