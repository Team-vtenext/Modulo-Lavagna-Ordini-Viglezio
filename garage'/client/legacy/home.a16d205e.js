import{_ as t,a as n,b as c,c as a,i as r,s as o,d as e,S as s,R as i,e as u,f as l,g as f,h as d,l as h,k as p,m,Z as v,n as g,o as b,r as _,T as $,H as E,I as y,y as x,z as I,A as k,B as w,a0 as D,E as S,F as A,G as T,J as V,a3 as L,L as N,a4 as j,M as P,N as C,O as R,a1 as z,t as M,j as O,u as B,P as H,a2 as q,$ as K,Y as Q,q as W,K as J,v as U}from"./client.5db13843.js";import{j as F}from"./jquery.e1982201.js";import{M as Y,K as G}from"./Modal.cde7e1e3.js";function X(t){var a=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}();return function(){var r,o=n(t);if(a){var e=n(this).constructor;r=Reflect.construct(o,arguments,e)}else r=o.apply(this,arguments);return c(this,r)}}var Z=function(t){return{}},tt=function(t){return{}},nt=function(t){return{}},ct=function(t){return{}};function at(t){var n,c,a,r,o,e,s,x,I=t[5].header,k=i(I,t,t[4],ct),w=t[5].body,D=i(w,t,t[4],tt);return{c:function(){n=u("article"),c=l(),a=u("div"),r=u("div"),k&&k.c(),o=l(),e=u("div"),D&&D.c(),this.h()},l:function(t){n=f(t,"ARTICLE",{class:!0});var s=d(n);c=h(s),a=f(s,"DIV",{class:!0});var i=d(a);r=f(i,"DIV",{class:!0,style:!0});var u=d(r);k&&k.l(u),u.forEach(p),o=h(i),e=f(i,"DIV",{class:!0,style:!0});var l=d(e);D&&D.l(l),l.forEach(p),i.forEach(p),s.forEach(p),this.h()},h:function(){m(r,"class","card-header svelte-1lifmcr"),v(r,"background-color",t[1]),v(r,"color",t[2]),m(e,"class","card-body svelte-1lifmcr"),v(e,"padding-top","0.25rem"),m(a,"class",s="card border-dark "+(1==t[0]?"square pulse2":"")+" svelte-1lifmcr"),m(n,"class","svelte-1lifmcr")},m:function(t,s){g(t,n,s),b(n,c),b(n,a),b(a,r),k&&k.m(r,null),b(a,o),b(a,e),D&&D.m(e,null),x=!0},p:function(t,n){var c=_(n,1)[0];k&&k.p&&16&c&&$(k,I,t,t[4],c,nt,ct),(!x||2&c)&&v(r,"background-color",t[1]),(!x||4&c)&&v(r,"color",t[2]),D&&D.p&&16&c&&$(D,w,t,t[4],c,Z,tt),(!x||1&c&&s!==(s="card border-dark "+(1==t[0]?"square pulse2":"")+" svelte-1lifmcr"))&&m(a,"class",s)},i:function(t){x||(E(!1),E(k,t),E(D,t),x=!0)},o:function(t){y(!1),y(k,t),y(D,t),x=!1},d:function(t){t&&p(n),k&&k.d(t),D&&D.d(t)}}}function rt(t,n,c){var a,r,o,e,s,i,u,l,f,d,h,p,m,v,g,b=n.currentDate,_=n.pulse;x(I(k.mark((function t(){var n,_,$,E;return k.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return n=b.split("-"),c(3,b=new Date(+n[2],n[1]-1,+n[0])),_=b.getDay(),t.next=5,fetch("https://lepori.vtenext.ch/modules/SDK/src/modules/Webservices/boardBackend.php?action=getSettings",{method:"POST",headers:{"Content-Type":"application/json"}});case 5:return $=t.sent,t.t0=w,t.next=9,$.json();case 9:t.t1=t.sent,E=(0,t.t0)(t.t1),o=E[0].mondaycol,e=E[0].tuesdaycol,s=E[0].wednesdaycol,i=E[0].thurstdaycol,u=E[0].fridaycol,l=E[0].saturdaycol,f=E[0].sundaycol,d=1==E[0].mondaytoggle?"#000":"#fff",h=1==E[0].tuesdaytoggle?"#000":"#fff",p=1==E[0].wednesdaytoggle?"#000":"#fff",m=1==E[0].thurstdaytoggle?"#000":"#fff",1==E[0].fridaytoggle?"#000":"#fff",v=1==E[0].saturdaytoggle?"#000":"#fff",g=1==E[0].sundaytoggle?"#000":"#fff",t.t2=_,t.next=0===t.t2?28:1===t.t2?31:2===t.t2?34:3===t.t2?37:4===t.t2?40:5===t.t2?43:6===t.t2?46:49;break;case 28:return c(1,a=f),c(2,r=g),t.abrupt("break",49);case 31:return c(1,a=o),c(2,r=d),t.abrupt("break",49);case 34:return c(1,a=e),c(2,r=h),t.abrupt("break",49);case 37:return c(1,a=s),c(2,r=p),t.abrupt("break",49);case 40:return c(1,a=i),c(2,r=m),t.abrupt("break",49);case 43:return c(1,a=u),c(2,r=u),t.abrupt("break",49);case 46:return c(1,a=l),c(2,r=v),t.abrupt("break",49);case 49:case"end":return t.stop()}}),t)}))));var $=n.$$slots,E=void 0===$?{}:$,y=n.$$scope;return t.$$set=function(t){"currentDate"in t&&c(3,b=t.currentDate),"pulse"in t&&c(0,_=t.pulse),"$$scope"in t&&c(4,y=t.$$scope)},[_,a,r,b,y,E]}var ot=function(n){t(i,s);var c=X(i);function i(t){var n;return a(this,i),n=c.call(this),r(e(n),t,rt,at,o,{currentDate:3,pulse:0}),n}return i}();function et(t){var a=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}();return function(){var r,o=n(t);if(a){var e=n(this).constructor;r=Reflect.construct(o,arguments,e)}else r=o.apply(this,arguments);return c(this,r)}}var st=j.document;function it(t,n,c){var a=t.slice();return a[32]=n[c],a[34]=c,a}function ut(t,n,c){var a=t.slice();return a[29]=n[c],a}function lt(t){return{c:B,l:B,m:B,p:B,i:B,o:B,d:B}}function ft(t){for(var n,c,a,r,o=t[7],e=[],s=0;s<o.length;s+=1)e[s]=Rt(ut(t,o,s));var i=function(t){return y(e[t],1,1,(function(){e[t]=null}))},l=null;return o.length||(l=dt()),{c:function(){n=u("div"),c=u("div"),a=u("div");for(var t=0;t<e.length;t+=1)e[t].c();l&&l.c(),this.h()},l:function(t){n=f(t,"DIV",{class:!0});var r=d(n);c=f(r,"DIV",{class:!0});var o=d(c);a=f(o,"DIV",{class:!0});for(var s=d(a),i=0;i<e.length;i+=1)e[i].l(s);l&&l.l(s),s.forEach(p),o.forEach(p),r.forEach(p),this.h()},h:function(){m(a,"class","card-columns"),m(c,"class","col"),m(n,"class","row")},m:function(t,o){g(t,n,o),b(n,c),b(c,a);for(var s=0;s<e.length;s+=1)e[s].m(a,null);l&&l.m(a,null),r=!0},p:function(t,n){if(1222&n[0]){var c;for(o=t[7],c=0;c<o.length;c+=1){var r=ut(t,o,c);e[c]?(e[c].p(r,n),E(e[c],1)):(e[c]=Rt(r),e[c].c(),E(e[c],1),e[c].m(a,null))}for(H(),c=o.length;c<e.length;c+=1)i(c);N(),o.length?l&&(l.d(1),l=null):l||((l=dt()).c(),l.m(a,null))}},i:function(t){if(!r){for(var n=0;n<o.length;n+=1)E(e[n]);r=!0}},o:function(t){e=e.filter(Boolean);for(var n=0;n<e.length;n+=1)y(e[n]);r=!1},d:function(t){t&&p(n),q(e,t),l&&l.d()}}}function dt(t){var n,c;return{c:function(){n=u("script"),c=M('var dialog = bootbox.dialog({\r\n\t\t\t\t\t\t\t\tcenterVertical: true,\r\n\t\t\t\t\t\t\t\tmessage: \'<h2><p class="text-center mb-0"><i class="fa fa-spin fas fa-tire fa-2x"></i> Loading</p></h2>\',\r\n\t\t\t\t\t\t\t\tcloseButton: false\r\n\t\t\t\t\t\t\t});\r\n\t\t\t\t\t')},l:function(t){n=f(t,"SCRIPT",{});var a=d(n);c=O(a,'var dialog = bootbox.dialog({\r\n\t\t\t\t\t\t\t\tcenterVertical: true,\r\n\t\t\t\t\t\t\t\tmessage: \'<h2><p class="text-center mb-0"><i class="fa fa-spin fas fa-tire fa-2x"></i> Loading</p></h2>\',\r\n\t\t\t\t\t\t\t\tcloseButton: false\r\n\t\t\t\t\t\t\t});\r\n\t\t\t\t\t'),a.forEach(p)},m:function(t,a){g(t,n,a),b(n,c)},d:function(t){t&&p(n)}}}function ht(t){var n,c,a,r,o,e,s,i,v,b,_=t[29].salesorder_no+"",$=null!=t[29].account_id&&""!=t[29].account_id&&vt(t),E=null!=t[29].contact_id&&" "!=t[29].contact_id&&gt(t);return{c:function(){n=u("i"),c=l(),a=M(_),r=M(" - "),$&&$.c(),o=l(),E&&E.c(),e=l(),s=u("i"),this.h()},l:function(t){n=f(t,"I",{class:!0}),d(n).forEach(p),c=h(t),a=O(t,_),r=O(t," - "),$&&$.l(t),o=h(t),E&&E.l(t),e=h(t),s=f(t,"I",{class:!0,"data-toggle":!0,id:!0,"data-placement":!0,title:!0}),d(s).forEach(p),this.h()},h:function(){m(n,"class","fas fa-pause-circle text-dark"),m(s,"class","fas fa-info-circle text-info"),m(s,"data-toggle","tooltip"),m(s,"id","test"),m(s,"data-placement","top"),m(s,"title",i=t[29].cf_hsn_1111)},m:function(i,u){g(i,n,u),g(i,c,u),g(i,a,u),g(i,r,u),$&&$.m(i,u),g(i,o,u),E&&E.m(i,u),g(i,e,u),g(i,s,u),v||(b=W(s,"click",(function(){z(Xt(t[29].cf_hsn_1111))&&Xt(t[29].cf_hsn_1111).apply(this,arguments)})),v=!0)},p:function(n,c){t=n,128&c[0]&&_!==(_=t[29].salesorder_no+"")&&K(a,_),null!=t[29].account_id&&""!=t[29].account_id?$?$.p(t,c):(($=vt(t)).c(),$.m(o.parentNode,o)):$&&($.d(1),$=null),null!=t[29].contact_id&&" "!=t[29].contact_id?E?E.p(t,c):((E=gt(t)).c(),E.m(e.parentNode,e)):E&&(E.d(1),E=null),128&c[0]&&i!==(i=t[29].cf_hsn_1111)&&m(s,"title",i)},d:function(t){t&&p(n),t&&p(c),t&&p(a),t&&p(r),$&&$.d(t),t&&p(o),E&&E.d(t),t&&p(e),t&&p(s),v=!1,b()}}}function pt(t){var n,c,a,r,o,e,s=t[29].salesorder_no+"",i=null!=t[29].account_id&&""!=t[29].account_id&&bt(t),v=null!=t[29].contact_id&&" "!=t[29].contact_id&&_t(t);return{c:function(){n=u("i"),c=l(),a=M(s),r=M(" - "),i&&i.c(),o=l(),v&&v.c(),e=Q(),this.h()},l:function(t){n=f(t,"I",{class:!0}),d(n).forEach(p),c=h(t),a=O(t,s),r=O(t," - "),i&&i.l(t),o=h(t),v&&v.l(t),e=Q(),this.h()},h:function(){m(n,"class","fas fa-car-garage text-dark")},m:function(t,s){g(t,n,s),g(t,c,s),g(t,a,s),g(t,r,s),i&&i.m(t,s),g(t,o,s),v&&v.m(t,s),g(t,e,s)},p:function(t,n){128&n[0]&&s!==(s=t[29].salesorder_no+"")&&K(a,s),null!=t[29].account_id&&""!=t[29].account_id?i?i.p(t,n):((i=bt(t)).c(),i.m(o.parentNode,o)):i&&(i.d(1),i=null),null!=t[29].contact_id&&" "!=t[29].contact_id?v?v.p(t,n):((v=_t(t)).c(),v.m(e.parentNode,e)):v&&(v.d(1),v=null)},d:function(t){t&&p(n),t&&p(c),t&&p(a),t&&p(r),i&&i.d(t),t&&p(o),v&&v.d(t),t&&p(e)}}}function mt(t){var n,c,a,r,o,e,s=t[29].salesorder_no+"",i=null!=t[29].account_id&&""!=t[29].account_id&&$t(t),v=null!=t[29].contact_id&&" "!=t[29].contact_id&&Et(t);return{c:function(){n=u("i"),c=l(),a=M(s),r=M(" - "),i&&i.c(),o=l(),v&&v.c(),e=Q(),this.h()},l:function(t){n=f(t,"I",{class:!0}),d(n).forEach(p),c=h(t),a=O(t,s),r=O(t," - "),i&&i.l(t),o=h(t),v&&v.l(t),e=Q(),this.h()},h:function(){m(n,"class","fas fa-car-crash text-dark")},m:function(t,s){g(t,n,s),g(t,c,s),g(t,a,s),g(t,r,s),i&&i.m(t,s),g(t,o,s),v&&v.m(t,s),g(t,e,s)},p:function(t,n){128&n[0]&&s!==(s=t[29].salesorder_no+"")&&K(a,s),null!=t[29].account_id&&""!=t[29].account_id?i?i.p(t,n):((i=$t(t)).c(),i.m(o.parentNode,o)):i&&(i.d(1),i=null),null!=t[29].contact_id&&" "!=t[29].contact_id?v?v.p(t,n):((v=Et(t)).c(),v.m(e.parentNode,e)):v&&(v.d(1),v=null)},d:function(t){t&&p(n),t&&p(c),t&&p(a),t&&p(r),i&&i.d(t),t&&p(o),v&&v.d(t),t&&p(e)}}}function vt(t){var n,c=t[29].account_id+"";return{c:function(){n=M(c)},l:function(t){n=O(t,c)},m:function(t,c){g(t,n,c)},p:function(t,a){128&a[0]&&c!==(c=t[29].account_id+"")&&K(n,c)},d:function(t){t&&p(n)}}}function gt(t){var n,c,a=t[29].contact_id+"";return{c:function(){n=M(", "),c=M(a)},l:function(t){n=O(t,", "),c=O(t,a)},m:function(t,a){g(t,n,a),g(t,c,a)},p:function(t,n){128&n[0]&&a!==(a=t[29].contact_id+"")&&K(c,a)},d:function(t){t&&p(n),t&&p(c)}}}function bt(t){var n,c=t[29].account_id+"";return{c:function(){n=M(c)},l:function(t){n=O(t,c)},m:function(t,c){g(t,n,c)},p:function(t,a){128&a[0]&&c!==(c=t[29].account_id+"")&&K(n,c)},d:function(t){t&&p(n)}}}function _t(t){var n,c,a=t[29].contact_id+"";return{c:function(){n=M(", "),c=M(a)},l:function(t){n=O(t,", "),c=O(t,a)},m:function(t,a){g(t,n,a),g(t,c,a)},p:function(t,n){128&n[0]&&a!==(a=t[29].contact_id+"")&&K(c,a)},d:function(t){t&&p(n),t&&p(c)}}}function $t(t){var n,c=t[29].account_id+"";return{c:function(){n=M(c)},l:function(t){n=O(t,c)},m:function(t,c){g(t,n,c)},p:function(t,a){128&a[0]&&c!==(c=t[29].account_id+"")&&K(n,c)},d:function(t){t&&p(n)}}}function Et(t){var n,c,a=t[29].contact_id+"";return{c:function(){n=M(", "),c=M(a)},l:function(t){n=O(t,", "),c=O(t,a)},m:function(t,a){g(t,n,a),g(t,c,a)},p:function(t,n){128&n[0]&&a!==(a=t[29].contact_id+"")&&K(c,a)},d:function(t){t&&p(n),t&&p(c)}}}function yt(t){var n,c,a,r,o,e,s,i,v,_=t[29].duedate+"";function $(t,n){return"Approved"==t[29].sostatus?mt:"Under Processing"==t[29].sostatus?pt:"On Hold"==t[29].sostatus?ht:void 0}var E=$(t),y=E&&E(t);return{c:function(){n=u("div"),c=u("div"),a=u("div"),y&&y.c(),r=l(),o=u("div"),e=u("small"),s=u("i"),i=l(),v=M(_),this.h()},l:function(t){n=f(t,"DIV",{slot:!0});var u=d(n);c=f(u,"DIV",{class:!0});var l=d(c);a=f(l,"DIV",{class:!0});var m=d(a);y&&y.l(m),m.forEach(p),r=h(l),o=f(l,"DIV",{class:!0});var g=d(o);e=f(g,"SMALL",{});var b=d(e);s=f(b,"I",{class:!0}),d(s).forEach(p),i=h(b),v=O(b,_),b.forEach(p),g.forEach(p),l.forEach(p),u.forEach(p),this.h()},h:function(){m(a,"class","col-8"),m(s,"class","fas fa-clock"),m(o,"class","col-4 text-right"),m(c,"class","row"),m(n,"slot","header")},m:function(t,u){g(t,n,u),b(n,c),b(c,a),y&&y.m(a,null),b(c,r),b(c,o),b(o,e),b(e,s),b(e,i),b(e,v)},p:function(t,n){E===(E=$(t))&&y?y.p(t,n):(y&&y.d(1),(y=E&&E(t))&&(y.c(),y.m(a,null))),128&n[0]&&_!==(_=t[29].duedate+"")&&K(v,_)},d:function(t){t&&p(n),y&&y.d()}}}function xt(t){var n,c,a,r,o,e=Gt(t[29].product_block.products)+"";return{c:function(){n=u("span"),c=u("h5"),a=M(e),r=M(t[1]),o=M("s"),this.h()},l:function(s){n=f(s,"SPAN",{style:!0});var i=d(n);c=f(i,"H5",{});var u=d(c);a=O(u,e),r=O(u,t[1]),o=O(u,"s"),u.forEach(p),i.forEach(p),this.h()},h:function(){v(n,"color","red")},m:function(t,e){g(t,n,e),b(n,c),b(c,a),b(c,r),b(c,o)},p:function(t,n){128&n[0]&&e!==(e=Gt(t[29].product_block.products)+"")&&K(a,e),2&n[0]&&K(r,t[1])},d:function(t){t&&p(n)}}}function It(t){var n,c,a,r,o,e,s=t[29].cf_hsn_1064.marca+"",i=t[29].cf_hsn_1064.modello+"",u=t[29].cf_hsn_1064.targa+"";return{c:function(){n=M(s),c=l(),a=M(i),r=M(" ("),o=M(u),e=M(")")},l:function(t){n=O(t,s),c=h(t),a=O(t,i),r=O(t," ("),o=O(t,u),e=O(t,")")},m:function(t,s){g(t,n,s),g(t,c,s),g(t,a,s),g(t,r,s),g(t,o,s),g(t,e,s)},p:function(t,c){128&c[0]&&s!==(s=t[29].cf_hsn_1064.marca+"")&&K(n,s),128&c[0]&&i!==(i=t[29].cf_hsn_1064.modello+"")&&K(a,i),128&c[0]&&u!==(u=t[29].cf_hsn_1064.targa+"")&&K(o,u)},d:function(t){t&&p(n),t&&p(c),t&&p(a),t&&p(r),t&&p(o),t&&p(e)}}}function kt(t){var n,c,a,r,o,e,s,i,v,_,$,E,y,x,I,k=t[32].productName+"",w=t[32].productDescription+"",D=""!=t[32].comment&&At(t);function S(){for(var n,c=arguments.length,a=new Array(c),r=0;r<c;r++)a[r]=arguments[r];return(n=t)[14].apply(n,[t[29],t[32]].concat(a))}return{c:function(){n=u("a"),c=u("div"),a=u("h5"),r=u("i"),o=M(" "),e=M(k),s=l(),i=u("small"),v=u("i"),_=l(),$=u("p"),E=M(w),y=l(),D&&D.c(),this.h()},l:function(t){n=f(t,"A",{href:!0,class:!0});var u=d(n);c=f(u,"DIV",{class:!0});var l=d(c);a=f(l,"H5",{class:!0});var m=d(a);r=f(m,"I",{class:!0}),d(r).forEach(p),o=O(m," "),e=O(m,k),m.forEach(p),s=h(l),i=f(l,"SMALL",{});var g=d(i);v=f(g,"I",{class:!0}),d(v).forEach(p),g.forEach(p),l.forEach(p),_=h(u),$=f(u,"P",{class:!0});var b=d($);E=O(b,w),b.forEach(p),y=h(u),D&&D.l(u),u.forEach(p),this.h()},h:function(){m(r,"class","fal fa-clipboard-list-check"),m(a,"class","mb-1"),m(v,"class","fas fa-user"),m(c,"class","d-flex w-100 justify-content-between"),m($,"class","mb-1"),m(n,"href","."),m(n,"class","list-group-item list-group-item-action")},m:function(t,u){g(t,n,u),b(n,c),b(c,a),b(a,r),b(a,o),b(a,e),b(c,s),b(c,i),b(i,v),b(n,_),b(n,$),b($,E),b(n,y),D&&D.m(n,null),x||(I=W(n,"click",J(S)),x=!0)},p:function(c,a){t=c,128&a[0]&&k!==(k=t[32].productName+"")&&K(e,k),128&a[0]&&w!==(w=t[32].productDescription+"")&&K(E,w),""!=t[32].comment?D?D.p(t,a):((D=At(t)).c(),D.m(n,null)):D&&(D.d(1),D=null)},d:function(t){t&&p(n),D&&D.d(),x=!1,I()}}}function wt(t){var n,c,a,r,o,e,s,i,v,_,$,E,y,x,I,k,w,D,S,A=t[32].productName+"",T=t[32].user+"",V=Yt(t[32].starttime,!0)+"",L=t[32].productDescription+"",N=""!=t[32].comment&&Tt(t);function j(){for(var n,c=arguments.length,a=new Array(c),r=0;r<c;r++)a[r]=arguments[r];return(n=t)[13].apply(n,[t[29],t[32]].concat(a))}return{c:function(){n=u("a"),c=u("div"),a=u("h5"),r=u("i"),o=M(" "),e=M(A),s=l(),i=u("small"),v=u("i"),_=M(" "),$=M(T),E=M(" | "),y=M(V),x=l(),I=u("p"),k=M(L),w=l(),N&&N.c(),this.h()},l:function(t){n=f(t,"A",{href:!0,class:!0});var u=d(n);c=f(u,"DIV",{class:!0});var l=d(c);a=f(l,"H5",{class:!0});var m=d(a);r=f(m,"I",{class:!0}),d(r).forEach(p),o=O(m," "),e=O(m,A),m.forEach(p),s=h(l),i=f(l,"SMALL",{});var g=d(i);v=f(g,"I",{class:!0}),d(v).forEach(p),_=O(g," "),$=O(g,T),E=O(g," | "),y=O(g,V),g.forEach(p),l.forEach(p),x=h(u),I=f(u,"P",{class:!0});var b=d(I);k=O(b,L),b.forEach(p),w=h(u),N&&N.l(u),u.forEach(p),this.h()},h:function(){m(r,"class","fal fa-clipboard-list-check"),m(a,"class","mb-1"),m(v,"class","fas fa-user-clock text-success"),m(c,"class","d-flex w-100 justify-content-between"),m(I,"class","mb-1"),m(n,"href","."),m(n,"class","list-group-item list-group-item-action list-group-item-info")},m:function(t,u){g(t,n,u),b(n,c),b(c,a),b(a,r),b(a,o),b(a,e),b(c,s),b(c,i),b(i,v),b(i,_),b(i,$),b(i,E),b(i,y),b(n,x),b(n,I),b(I,k),b(n,w),N&&N.m(n,null),D||(S=W(n,"click",J(j)),D=!0)},p:function(c,a){t=c,128&a[0]&&A!==(A=t[32].productName+"")&&K(e,A),128&a[0]&&T!==(T=t[32].user+"")&&K($,T),128&a[0]&&V!==(V=Yt(t[32].starttime,!0)+"")&&K(y,V),128&a[0]&&L!==(L=t[32].productDescription+"")&&K(k,L),""!=t[32].comment?N?N.p(t,a):((N=Tt(t)).c(),N.m(n,null)):N&&(N.d(1),N=null)},d:function(t){t&&p(n),N&&N.d(),D=!1,S()}}}function Dt(t){var n,c,a,r,o,e,s,i,v,_,$,E,y,x,I,k,w,D,S,A,T,V,L,N=t[32].productName+"",j=t[32].user+"",P=Yt(t[32].starttime,!0)+"",C=t[32].productDescription+"",R=""!=t[32].comment&&Vt(t);function z(){for(var n,c=arguments.length,a=new Array(c),r=0;r<c;r++)a[r]=arguments[r];return(n=t)[12].apply(n,[t[29],t[32]].concat(a))}return{c:function(){n=u("a"),c=u("div"),a=u("h5"),r=u("i"),o=M(" "),e=M(N),s=l(),i=u("small"),v=u("i"),_=M(" "),$=M(j),E=u("br"),y=l(),x=u("h6"),I=M(P),k=M(t[1]),w=M("s"),D=l(),S=u("p"),A=M(C),T=l(),R&&R.c(),this.h()},l:function(u){n=f(u,"A",{href:!0,class:!0});var l=d(n);c=f(l,"DIV",{class:!0});var m=d(c);a=f(m,"H5",{class:!0});var g=d(a);r=f(g,"I",{class:!0}),d(r).forEach(p),o=O(g," "),e=O(g,N),g.forEach(p),s=h(m),i=f(m,"SMALL",{});var b=d(i);v=f(b,"I",{class:!0}),d(v).forEach(p),_=O(b," "),$=O(b,j),E=f(b,"BR",{}),y=h(b),x=f(b,"H6",{});var V=d(x);I=O(V,P),k=O(V,t[1]),w=O(V,"s"),V.forEach(p),b.forEach(p),m.forEach(p),D=h(l),S=f(l,"P",{class:!0});var L=d(S);A=O(L,C),L.forEach(p),T=h(l),R&&R.l(l),l.forEach(p),this.h()},h:function(){m(r,"class","fal fa-clipboard-list-check"),m(a,"class","mb-1"),m(v,"class","fas fa-user-clock text-warning"),m(c,"class","d-flex w-100 justify-content-between"),m(S,"class","mb-1"),m(n,"href","."),m(n,"class","list-group-item list-group-item-action list-group-item-warning")},m:function(t,u){g(t,n,u),b(n,c),b(c,a),b(a,r),b(a,o),b(a,e),b(c,s),b(c,i),b(i,v),b(i,_),b(i,$),b(i,E),b(i,y),b(i,x),b(x,I),b(x,k),b(x,w),b(n,D),b(n,S),b(S,A),b(n,T),R&&R.m(n,null),V||(L=W(n,"click",J(z)),V=!0)},p:function(c,a){t=c,128&a[0]&&N!==(N=t[32].productName+"")&&K(e,N),128&a[0]&&j!==(j=t[32].user+"")&&K($,j),128&a[0]&&P!==(P=Yt(t[32].starttime,!0)+"")&&K(I,P),2&a[0]&&K(k,t[1]),128&a[0]&&C!==(C=t[32].productDescription+"")&&K(A,C),""!=t[32].comment?R?R.p(t,a):((R=Vt(t)).c(),R.m(n,null)):R&&(R.d(1),R=null)},d:function(t){t&&p(n),R&&R.d(),V=!1,L()}}}function St(t){var n,c=0==t[6]&&Lt(t);return{c:function(){c&&c.c(),n=Q()},l:function(t){c&&c.l(t),n=Q()},m:function(t,a){c&&c.m(t,a),g(t,n,a)},p:function(t,a){0==t[6]?c?c.p(t,a):((c=Lt(t)).c(),c.m(n.parentNode,n)):c&&(c.d(1),c=null)},d:function(t){c&&c.d(t),t&&p(n)}}}function At(t){var n,c,a,r,o=t[32].comment+"";return{c:function(){n=u("small"),c=u("i"),a=M(" "),r=M(o),this.h()},l:function(t){n=f(t,"SMALL",{});var e=d(n);c=f(e,"I",{class:!0}),d(c).forEach(p),a=O(e," "),r=O(e,o),e.forEach(p),this.h()},h:function(){m(c,"class","fal fa-comment-lines")},m:function(t,o){g(t,n,o),b(n,c),b(n,a),b(n,r)},p:function(t,n){128&n[0]&&o!==(o=t[32].comment+"")&&K(r,o)},d:function(t){t&&p(n)}}}function Tt(t){var n,c,a,r,o=t[32].comment+"";return{c:function(){n=u("small"),c=u("i"),a=M(" "),r=M(o),this.h()},l:function(t){n=f(t,"SMALL",{});var e=d(n);c=f(e,"I",{class:!0}),d(c).forEach(p),a=O(e," "),r=O(e,o),e.forEach(p),this.h()},h:function(){m(c,"class","fal fa-comment-lines")},m:function(t,o){g(t,n,o),b(n,c),b(n,a),b(n,r)},p:function(t,n){128&n[0]&&o!==(o=t[32].comment+"")&&K(r,o)},d:function(t){t&&p(n)}}}function Vt(t){var n,c,a,r,o=t[32].comment+"";return{c:function(){n=u("small"),c=u("i"),a=M(" "),r=M(o),this.h()},l:function(t){n=f(t,"SMALL",{});var e=d(n);c=f(e,"I",{class:!0}),d(c).forEach(p),a=O(e," "),r=O(e,o),e.forEach(p),this.h()},h:function(){m(c,"class","fal fa-comment-lines")},m:function(t,o){g(t,n,o),b(n,c),b(n,a),b(n,r)},p:function(t,n){128&n[0]&&o!==(o=t[32].comment+"")&&K(r,o)},d:function(t){t&&p(n)}}}function Lt(t){var n,c,a,r,o,e,s,i,v,_,$,E,y,x,I,k,w,D,S,A,T=t[32].productName+"",V=t[32].user+"",L=Yt(t[32].starttime,!0)+"",N=t[32].productDescription+"",j=""!=t[32].comment&&Nt(t);function P(){for(var n,c=arguments.length,a=new Array(c),r=0;r<c;r++)a[r]=arguments[r];return(n=t)[11].apply(n,[t[29],t[32]].concat(a))}return{c:function(){n=u("a"),c=u("div"),a=u("h5"),r=u("i"),o=M(" \r\n\t\t\t\t\t\t\t\t\t\t\t"),e=M(T),s=l(),i=u("small"),v=u("i"),_=M(" "),$=M(V),E=l(),y=u("h6"),x=M(L),I=l(),k=u("p"),w=M(N),D=l(),j&&j.c(),this.h()},l:function(t){n=f(t,"A",{href:!0,class:!0});var u=d(n);c=f(u,"DIV",{class:!0});var l=d(c);a=f(l,"H5",{class:!0});var m=d(a);r=f(m,"I",{class:!0});var g=d(r);o=O(g," \r\n\t\t\t\t\t\t\t\t\t\t\t"),g.forEach(p),e=O(m,T),m.forEach(p),s=h(l),i=f(l,"SMALL",{});var b=d(i);v=f(b,"I",{class:!0}),d(v).forEach(p),_=O(b," "),$=O(b,V),E=h(b),y=f(b,"H6",{});var S=d(y);x=O(S,L),S.forEach(p),b.forEach(p),l.forEach(p),I=h(u),k=f(u,"P",{class:!0});var A=d(k);w=O(A,N),A.forEach(p),D=h(u),j&&j.l(u),u.forEach(p),this.h()},h:function(){m(r,"class","fal fa-check text-success"),m(a,"class","mb-1"),m(v,"class","fas fa-user-check"),m(c,"class","d-flex w-100 justify-content-between"),m(k,"class","mb-1"),m(n,"href","."),m(n,"class","list-group-item list-group-item-action list-group-item-success")},m:function(t,u){g(t,n,u),b(n,c),b(c,a),b(a,r),b(r,o),b(a,e),b(c,s),b(c,i),b(i,v),b(i,_),b(i,$),b(i,E),b(i,y),b(y,x),b(n,I),b(n,k),b(k,w),b(n,D),j&&j.m(n,null),S||(A=W(n,"click",J(P)),S=!0)},p:function(c,a){t=c,128&a[0]&&T!==(T=t[32].productName+"")&&K(e,T),128&a[0]&&V!==(V=t[32].user+"")&&K($,V),128&a[0]&&L!==(L=Yt(t[32].starttime,!0)+"")&&K(x,L),128&a[0]&&N!==(N=t[32].productDescription+"")&&K(w,N),""!=t[32].comment?j?j.p(t,a):((j=Nt(t)).c(),j.m(n,null)):j&&(j.d(1),j=null)},d:function(t){t&&p(n),j&&j.d(),S=!1,A()}}}function Nt(t){var n,c,a,r,o=t[32].comment+"";return{c:function(){n=u("small"),c=u("i"),a=M(" "),r=M(o),this.h()},l:function(t){n=f(t,"SMALL",{});var e=d(n);c=f(e,"I",{class:!0}),d(c).forEach(p),a=O(e," "),r=O(e,o),e.forEach(p),this.h()},h:function(){m(c,"class","fal fa-comment-lines")},m:function(t,o){g(t,n,o),b(n,c),b(n,a),b(n,r)},p:function(t,n){128&n[0]&&o!==(o=t[32].comment+"")&&K(r,o)},d:function(t){t&&p(n)}}}function jt(t){var n;function c(t,n){return"Concluso"==t[32].current_status?St:"In Corso"==t[32].current_status?Dt:"Pausa"==t[32].current_status?wt:kt}var a=c(t),r=a(t);return{c:function(){r.c(),n=Q()},l:function(t){r.l(t),n=Q()},m:function(t,c){r.m(t,c),g(t,n,c)},p:function(t,o){a===(a=c(t))&&r?r.p(t,o):(r.d(1),(r=a(t))&&(r.c(),r.m(n.parentNode,n)))},d:function(t){r.d(t),t&&p(n)}}}function Pt(t){for(var n,c,a,r,o,e,s,i,v,_,$,E,y,x,I,k,w,D,S,A,T,V,L,N,j,P,C,R,z,B,H,Q,F,Y,G,X,Z,tt,nt,ct,at,rt,ot=t[29].cf_hsn_1064.targa+"",et=t[29].veicoliid.marca+"",st=t[29].veicoliid.modello+"",ut=""!=Gt(t[29].product_block.products),lt=t[29].description+"",ft=ut&&xt(t),dt=""!=t[29].cf_hsn_1064.targa&&null!=t[29].cf_hsn_1064.targa&&It(t),ht=t[29].product_block.products,pt=[],mt=0;mt<ht.length;mt+=1)pt[mt]=jt(it(t,ht,mt));function vt(){for(var n,c=arguments.length,a=new Array(c),r=0;r<c;r++)a[r]=arguments[r];return(n=t)[15].apply(n,[t[29]].concat(a))}return{c:function(){n=u("div"),c=u("div"),a=u("div"),r=u("div"),o=u("h5"),e=M(ot),s=u("br"),i=M(et),v=M(", "),_=M(st),$=M(" "),E=l(),ft&&ft.c(),y=l(),x=u("div"),I=u("i"),k=M(" Auto di Cortesia"),w=u("br"),D=l(),dt&&dt.c(),S=l(),A=u("div"),T=u("div"),V=u("small"),L=M(lt),N=l(),j=u("div");for(var t=0;t<pt.length;t+=1)pt[t].c();P=l(),C=u("br"),z=l(),B=u("div"),H=u("div"),Q=u("a"),F=u("i"),Y=M(" Chiudi"),G=l(),X=u("div"),Z=u("a"),tt=u("i"),this.h()},l:function(t){n=f(t,"DIV",{slot:!0});var u=d(n);c=f(u,"DIV",{class:!0});var l=d(c);a=f(l,"DIV",{class:!0});var m=d(a);r=f(m,"DIV",{class:!0});var g=d(r);o=f(g,"H5",{class:!0});var b=d(o);e=O(b,ot),s=f(b,"BR",{}),i=O(b,et),v=O(b,", "),_=O(b,st),$=O(b," "),b.forEach(p),g.forEach(p),E=h(m),ft&&ft.l(m),y=h(m),x=f(m,"DIV",{class:!0});var R=d(x);I=f(R,"I",{class:!0}),d(I).forEach(p),k=O(R," Auto di Cortesia"),w=f(R,"BR",{}),D=h(R),dt&&dt.l(R),R.forEach(p),m.forEach(p),S=h(l),A=f(l,"DIV",{class:!0});var M=d(A);T=f(M,"DIV",{class:!0});var q=d(T);V=f(q,"SMALL",{});var K=d(V);L=O(K,lt),K.forEach(p),q.forEach(p),M.forEach(p),l.forEach(p),N=h(u),j=f(u,"DIV",{class:!0,id:!0});for(var W=d(j),J=0;J<pt.length;J+=1)pt[J].l(W);P=h(W),C=f(W,"BR",{}),W.forEach(p),z=h(u),B=f(u,"DIV",{class:!0});var U=d(B);H=f(U,"DIV",{class:!0});var nt=d(H);Q=f(nt,"A",{href:!0,class:!0});var ct=d(Q);F=f(ct,"I",{class:!0}),d(F).forEach(p),Y=O(ct," Chiudi"),ct.forEach(p),nt.forEach(p),G=h(U),X=f(U,"DIV",{class:!0});var at=d(X);Z=f(at,"A",{class:!0,"data-toggle":!0,href:!0,role:!0,"aria-expanded":!0,"aria-controls":!0});var rt=d(Z);tt=f(rt,"I",{class:!0}),d(tt).forEach(p),rt.forEach(p),at.forEach(p),U.forEach(p),u.forEach(p),this.h()},h:function(){m(o,"class","card-title text-dark"),m(r,"class","col"),m(I,"class","fas fa-car text-danger"),m(x,"class","col text-right"),m(a,"class","row"),m(T,"class","col"),m(A,"class","row"),m(c,"class","card-text mb-3"),m(j,"class","list-group mb-3 collapse"),m(j,"id",R="collapse"+t[29].id),m(F,"class","fas fa-check"),m(Q,"href","."),m(Q,"class","btn btn-success"),m(H,"class","col-5"),m(tt,"class","fas fa-plus-square fa-2x"),m(Z,"class","text-info"),m(Z,"data-toggle","collapse"),m(Z,"href",nt="#collapse"+t[29].id),m(Z,"role","button"),m(Z,"aria-expanded","false"),m(Z,"aria-controls",ct="collapse"+t[29].id),m(X,"class","col-2 text-center"),m(B,"class","row"),m(n,"slot","body")},m:function(u,l){g(u,n,l),b(n,c),b(c,a),b(a,r),b(r,o),b(o,e),b(o,s),b(o,i),b(o,v),b(o,_),b(o,$),b(a,E),ft&&ft.m(a,null),b(a,y),b(a,x),b(x,I),b(x,k),b(x,w),b(x,D),dt&&dt.m(x,null),b(c,S),b(c,A),b(A,T),b(T,V),b(V,L),b(n,N),b(n,j);for(var f=0;f<pt.length;f+=1)pt[f].m(j,null);b(j,P),b(j,C),b(n,z),b(n,B),b(B,H),b(H,Q),b(Q,F),b(Q,Y),b(B,G),b(B,X),b(X,Z),b(Z,tt),at||(rt=[W(Q,"click",J(vt)),W(tt,"click",t[16])],at=!0)},p:function(n,c){if(t=n,128&c[0]&&ot!==(ot=t[29].cf_hsn_1064.targa+"")&&K(e,ot),128&c[0]&&et!==(et=t[29].veicoliid.marca+"")&&K(i,et),128&c[0]&&st!==(st=t[29].veicoliid.modello+"")&&K(_,st),128&c[0]&&(ut=""!=Gt(t[29].product_block.products)),ut?ft?ft.p(t,c):((ft=xt(t)).c(),ft.m(a,y)):ft&&(ft.d(1),ft=null),""!=t[29].cf_hsn_1064.targa&&null!=t[29].cf_hsn_1064.targa?dt?dt.p(t,c):((dt=It(t)).c(),dt.m(x,null)):dt&&(dt.d(1),dt=null),128&c[0]&&lt!==(lt=t[29].description+"")&&K(L,lt),1218&c[0]){var r;for(ht=t[29].product_block.products,r=0;r<ht.length;r+=1){var o=it(t,ht,r);pt[r]?pt[r].p(o,c):(pt[r]=jt(o),pt[r].c(),pt[r].m(j,P))}for(;r<pt.length;r+=1)pt[r].d(1);pt.length=ht.length}128&c[0]&&R!==(R="collapse"+t[29].id)&&m(j,"id",R),128&c[0]&&nt!==(nt="#collapse"+t[29].id)&&m(Z,"href",nt),128&c[0]&&ct!==(ct="collapse"+t[29].id)&&m(Z,"aria-controls",ct)},d:function(t){t&&p(n),ft&&ft.d(),dt&&dt.d(),q(pt,t),at=!1,U(rt)}}}function Ct(t){var n,c;return{c:function(){n=l(),c=l()},l:function(t){n=h(t),c=h(t)},m:function(t,a){g(t,n,a),g(t,c,a)},p:B,d:function(t){t&&p(n),t&&p(c)}}}function Rt(t){var n,c,a,r,o;return r=new ot({props:{currentDate:t[29].duedate,pulse:t[29].cf_hsn_1089,$$slots:{default:[Ct],body:[Pt],header:[yt]},$$scope:{ctx:t}}}),{c:function(){n=u("script"),c=M("dialog.modal('hide');"),a=l(),S(r.$$.fragment)},l:function(t){n=f(t,"SCRIPT",{});var o=d(n);c=O(o,"dialog.modal('hide');"),o.forEach(p),a=h(t),A(r.$$.fragment,t)},m:function(t,e){g(t,n,e),b(n,c),g(t,a,e),T(r,t,e),o=!0},p:function(t,n){var c={};128&n[0]&&(c.currentDate=t[29].duedate),128&n[0]&&(c.pulse=t[29].cf_hsn_1089),198&n[0]|16&n[1]&&(c.$$scope={dirty:n,ctx:t}),r.$set(c)},i:function(t){o||(E(r.$$.fragment,t),o=!0)},o:function(t){y(r.$$.fragment,t),o=!1},d:function(t){t&&p(n),t&&p(a),V(r,t)}}}function zt(t){var n,c,a,r,o;return{c:function(){n=u("script"),c=M('var dialog = bootbox.dialog({\r\n\t\tmessage: \'<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Please wait while we do something...</p>\',\r\n\t\tcloseButton: false\r\n\t});'),a=l(),r=u("style"),o=M(".pulse2 {\r\n  animation: pulse2 1.0s ease-out infinite;\r\n}\r\n\r\n@keyframes pulse2 {\r\n  50% {\r\n    box-shadow: 0 0 0 0.3em rgba(255, 0, 0, 0.712);\r\n  }\r\n  100% {\r\n    box-shadow: 0 0 0 0.6em rgba(255, 255, 255, 0);\r\n  }\r\n}\r\n\r\n.square {\r\n  border-radius: 0.6em;\r\n  box-sizing: border-box;\r\n  margin: 1em;\r\n  overflow: hidden;\r\n}")},l:function(t){n=f(t,"SCRIPT",{});var e=d(n);c=O(e,'var dialog = bootbox.dialog({\r\n\t\tmessage: \'<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Please wait while we do something...</p>\',\r\n\t\tcloseButton: false\r\n\t});'),e.forEach(p),a=h(t),r=f(t,"STYLE",{});var s=d(r);o=O(s,".pulse2 {\r\n  animation: pulse2 1.0s ease-out infinite;\r\n}\r\n\r\n@keyframes pulse2 {\r\n  50% {\r\n    box-shadow: 0 0 0 0.3em rgba(255, 0, 0, 0.712);\r\n  }\r\n  100% {\r\n    box-shadow: 0 0 0 0.6em rgba(255, 255, 255, 0);\r\n  }\r\n}\r\n\r\n.square {\r\n  border-radius: 0.6em;\r\n  box-sizing: border-box;\r\n  margin: 1em;\r\n  overflow: hidden;\r\n}"),s.forEach(p)},m:function(t,e){g(t,n,e),b(n,c),g(t,a,e),g(t,r,e),b(r,o)},p:B,i:B,o:B,d:function(t){t&&p(n),t&&p(a),t&&p(r)}}}function Mt(t){var n,c;return(n=new Y({props:{$$slots:{default:[Ot]},$$scope:{ctx:t}}})).$on("close",t[18]),{c:function(){S(n.$$.fragment)},l:function(t){A(n.$$.fragment,t)},m:function(t,a){T(n,t,a),c=!0},p:function(t,c){var a={};16&c[0]|16&c[1]&&(a.$$scope={dirty:c,ctx:t}),n.$set(a)},i:function(t){c||(E(n.$$.fragment,t),c=!0)},o:function(t){y(n.$$.fragment,t),c=!1},d:function(t){V(n,t)}}}function Ot(t){var n,c,a;function r(n){t[17].call(null,n)}var o={};return void 0!==t[4]&&(o.value=t[4]),n=new G({props:o}),P.push((function(){return C(n,"value",r)})),n.$on("submit",t[8]),{c:function(){S(n.$$.fragment)},l:function(t){A(n.$$.fragment,t)},m:function(t,c){T(n,t,c),a=!0},p:function(t,a){var r={};!c&&16&a[0]&&(c=!0,r.value=t[4],R((function(){return c=!1}))),n.$set(r)},i:function(t){a||(E(n.$$.fragment,t),a=!0)},o:function(t){y(n.$$.fragment,t),a=!1},d:function(t){V(n,t)}}}function Bt(t){var n,c;return(n=new Y({props:{$$slots:{default:[Ht]},$$scope:{ctx:t}}})).$on("close",t[20]),{c:function(){S(n.$$.fragment)},l:function(t){A(n.$$.fragment,t)},m:function(t,a){T(n,t,a),c=!0},p:function(t,c){var a={};40&c[0]|16&c[1]&&(a.$$scope={dirty:c,ctx:t}),n.$set(a)},i:function(t){c||(E(n.$$.fragment,t),c=!0)},o:function(t){y(n.$$.fragment,t),c=!1},d:function(t){V(n,t)}}}function Ht(t){var n,c,a;function r(n){t[19].call(null,n)}var o={};return void 0!==t[5]&&(o.value=t[5]),n=new G({props:o}),P.push((function(){return C(n,"value",r)})),n.$on("submit",(function(){z(t[9](t[3]))&&t[9](t[3]).apply(this,arguments)})),{c:function(){S(n.$$.fragment)},l:function(t){A(n.$$.fragment,t)},m:function(t,c){T(n,t,c),a=!0},p:function(a,r){t=a;var o={};!c&&32&r[0]&&(c=!0,o.value=t[5],R((function(){return c=!1}))),n.$set(o)},i:function(t){a||(E(n.$$.fragment,t),a=!0)},o:function(t){y(n.$$.fragment,t),a=!1},d:function(t){V(n,t)}}}function qt(t){var n,c;return(n=new Y({props:{$$slots:{default:[Qt],header:[Kt]},$$scope:{ctx:t}}})).$on("close",t[21]),{c:function(){S(n.$$.fragment)},l:function(t){A(n.$$.fragment,t)},m:function(t,a){T(n,t,a),c=!0},p:function(t,c){var a={};16&c[1]&&(a.$$scope={dirty:c,ctx:t}),n.$set(a)},i:function(t){c||(E(n.$$.fragment,t),c=!0)},o:function(t){y(n.$$.fragment,t),c=!1},d:function(t){V(n,t)}}}function Kt(t){var n,c;return{c:function(){n=u("h2"),c=M("Seleziona Operazione"),this.h()},l:function(t){n=f(t,"H2",{slot:!0});var a=d(n);c=O(a,"Seleziona Operazione"),a.forEach(p),this.h()},h:function(){m(n,"slot","header")},m:function(t,a){g(t,n,a),b(n,c)},d:function(t){t&&p(n)}}}function Qt(t){var n,c,a,r,o,e,s;return{c:function(){n=l(),c=u("div"),a=u("button"),r=M("In Pausa"),o=l(),e=u("button"),s=M("Termina"),this.h()},l:function(t){n=h(t),c=f(t,"DIV",{class:!0});var i=d(c);a=f(i,"BUTTON",{});var u=d(a);r=O(u,"In Pausa"),u.forEach(p),o=h(i),e=f(i,"BUTTON",{});var l=d(e);s=O(l,"Termina"),l.forEach(p),i.forEach(p),this.h()},h:function(){m(c,"class","col-2")},m:function(t,i){g(t,n,i),g(t,c,i),b(c,a),b(a,r),b(c,o),b(c,e),b(e,s)},p:B,d:function(t){t&&p(n),t&&p(c)}}}function Wt(t){var n,c,a,r,o,e,s,i={ctx:t,current:null,token:null,pending:zt,then:ft,catch:lt,value:7,blocks:[,,,]};D(a=t[7],i);var v=0!=t[2]&&Mt(t),_=0!=t[3]&&Bt(t),$=t[0]&&qt(t);return{c:function(){n=l(),c=u("main"),i.block.c(),r=l(),v&&v.c(),o=l(),_&&_.c(),e=l(),$&&$.c(),this.h()},l:function(t){L('[data-svelte="svelte-1tz223n"]',st.head).forEach(p),n=h(t),c=f(t,"MAIN",{role:!0,class:!0});var a=d(c);i.block.l(a),r=h(a),v&&v.l(a),o=h(a),_&&_.l(a),e=h(a),$&&$.l(a),a.forEach(p),this.h()},h:function(){st.title="Carrozzeria Lepori",m(c,"role","main"),m(c,"class","container-fluid")},m:function(t,a){g(t,n,a),g(t,c,a),i.block.m(c,i.anchor=null),i.mount=function(){return c},i.anchor=r,b(c,r),v&&v.m(c,null),b(c,o),_&&_.m(c,null),b(c,e),$&&$.m(c,null),s=!0},p:function(n,r){if(t=n,i.ctx=t,128&r[0]&&a!==(a=t[7])&&D(a,i));else{var s=t.slice();s[7]=i.resolved,i.block.p(s,r)}0!=t[2]?v?(v.p(t,r),4&r[0]&&E(v,1)):((v=Mt(t)).c(),E(v,1),v.m(c,o)):v&&(H(),y(v,1,1,(function(){v=null})),N()),0!=t[3]?_?(_.p(t,r),8&r[0]&&E(_,1)):((_=Bt(t)).c(),E(_,1),_.m(c,e)):_&&(H(),y(_,1,1,(function(){_=null})),N()),t[0]?$?($.p(t,r),1&r[0]&&E($,1)):(($=qt(t)).c(),E($,1),$.m(c,null)):$&&(H(),y($,1,1,(function(){$=null})),N())},i:function(t){s||(E(i.block),E(v),E(_),E($),s=!0)},o:function(t){for(var n=0;n<3;n+=1){var c=i.blocks[n];y(c)}y(v),y(_),y($),s=!1},d:function(t){t&&p(n),t&&p(c),i.block.d(),i.token=null,i=null,v&&v.d(),_&&_.d(),$&&$.d()}}}function Jt(t,n){if(!n.token)return this.redirect(302,"/")}var Ut="https://lepori.vtenext.ch/";function Ft(){return(Ft=I(k.mark((function t(n){var c,a;return k.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return c={crmid:n},t.next=3,fetch(Ut+"modules/SDK/src/modules/Webservices/backend.php?action=closeSalesOrder",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify(c)});case 3:return a=t.sent,t.abrupt("return",a.json());case 5:case"end":return t.stop()}}),t)})))).apply(this,arguments)}function Yt(t,n){var c=new Date(t).getTime();if(1==n)n=(new Date).getTime();else n=new Date(n).getTime();var a=n-c,r=a/1e3,o=a/60/1e3,e=a/3600/1e3,s={};return s.hours=Math.floor(e),s.minutes=o-60*s.hours,s.seconds=r%60,s.hours<1&&s.minutes>1?parseInt(s.minutes)+"min":parseInt(s.hours)+"h "+parseInt(s.minutes)+"min "}function Gt(t){var n,c=[];for(n=0;n<t.length;n++)"In Corso"==t[n].current_status&&c.push(t[n].starttime);var a=c.sort((function(t,n){return Date.parse(t)-Date.parse(n)}));return a[0]?Yt(a[0],!0):""}function Xt(t){bootbox.dialog({message:t,title:"Motivazione attesa",animate:!0,backdrop:!0,className:"small"})}function Zt(t,n,c){var a,r,o,e,s,i=!1,u=[],l=0,f=!1,d=!1;function h(t,n,a){bootbox.prompt("<h5 class='modal-title'>In attesa della carta</h5><br><small>Dimenticato la Tessera:</small>&nbsp;<input type='checkbox' id='missingcard' value='Dimenticato la tessera' />",(function(o){F("#missingcard").is(":checked")?(c(5,r=""),c(3,d=t+","+n+","+a)):null!==o&&""!=o&&p(t,n,a,o,!1,s).then((function(t){if("Success"!=t){var n=bootbox.alert({message:t,size:"small"});setTimeout((function(){n.modal("hide")}),5e3)}}))}))}function p(t,n,c,a,r,o){return m.apply(this,arguments)}function m(){return(m=I(k.mark((function t(n,c,a,r,o,e){var s,i;return k.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return s={authentication:r,crmid:n,lineItemId:c,inventoryField:"current_status",value:a,isPin:o,autoClose:e},t.next=3,fetch(Ut+"modules/SDK/src/modules/Webservices/backend.php?action=updateInventoryRow",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify(s)});case 3:return i=t.sent,v(),t.abrupt("return",i.json());case 6:case"end":return t.stop()}}),t)})))).apply(this,arguments)}function v(){return g.apply(this,arguments)}function g(){return(g=I(k.mark((function t(){var n,a;return k.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,fetch(Ut+"modules/SDK/src/modules/Webservices/backend.php?action=getOrders",{method:"POST",headers:{"Content-Type":"application/json"}});case 2:return n=t.sent,t.next=5,n.json();case 5:a=t.sent,c(1,l=0),c(7,u=a.data);case 8:case"end":return t.stop()}}),t)})))).apply(this,arguments)}function b(t,n,c){"In Corso"===c?bootbox.dialog({message:"<p>Seleziona se mettere in pausa o terminare l'attività selezionata</p>",size:"large",buttons:{pause:{label:"<i class='fas fa-pause'></i> Pausa Attività",className:"btn btn-dark btn-lg btn-block",callback:function(){h(t,n,c="Pausa")}},close:{label:"<i class='fas fa-check'></i> Termina Attività",className:"btn btn-success btn-lg btn-block",callback:function(){h(t,n,c="Concluso")}}}}):h(t,n,c="In Corso")}function _(){c(1,l+=1),60==l&&c(1,l=0)}x(I(k.mark((function t(){var n,a,r,i;return k.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return window.jQuery||(window.jQuery=F),"complete"!==(n=document.readyState)&&"loaded"!==n||window.jQuery((function(){window.jQuery('[data-toggle="tooltip"]').tooltip(),window.jQuery("#test").tooltip("show")})),t.next=5,fetch(Ut+"modules/SDK/src/modules/Webservices/boardBackend.php?action=getSettings",{method:"POST",headers:{"Content-Type":"application/json"}});case 5:return a=t.sent,t.t0=w,t.next=9,a.json();case 9:return t.t1=t.sent,r=(0,t.t0)(t.t1),o=r[0].pin,c(6,e=r[0].hidecompletedjobs),s=r[0].autocloseproject,i=setInterval(v,6e4),setInterval(_,1e3),v(),t.abrupt("return",(function(){return clearInterval(i)}));case 18:case"end":return t.stop()}}),t)}))));return[i,l,f,d,a,r,e,u,function(){a==o?(c(4,a=""),function(t){return Ft.apply(this,arguments)}(f).then((function(t){var n=bootbox.alert({message:t.error,size:"small"});setTimeout((function(){n.modal("hide")}),2e3)})),c(2,f=!1)):(c(4,a=""),bootbox.alert({message:"<p align='center'>PIN Errato</p>",animate:!0,size:"small",backdrop:!0,centerVertical:!0,className:"animate__animated animate__shakeX"}))},function(t){p((t=t.split(","))[0],t[1],t[2],r,!0,s).then((function(t){if(c(3,d=!1),"Success"!=t){var n=bootbox.alert({message:t,size:"small"});setTimeout((function(){n.modal("hide")}),5e3)}}))},b,function(t,n){return b(t.id,n.lineItemId,n.current_status)},function(t,n){return b(t.id,n.lineItemId,n.current_status)},function(t,n){return b(t.id,n.lineItemId,n.current_status)},function(t,n){return b(t.id,n.lineItemId,n.current_status)},function(t){return c(2,f=t.id)},function(t){return c=(n=t).path[0].classList,void(n.path[0].classList="fas fa-plus-square fa-2x"==c?"fas fa-minus-square fa-2x":"fas fa-plus-square fa-2x");var n,c},function(t){c(4,a=t)},function(){return c(0,i=!1)},function(t){c(5,r=t)},function(){return c(0,i=!1)},function(){return c(0,i=!1)}]}var tn=function(n){t(i,s);var c=et(i);function i(t){var n;return a(this,i),n=c.call(this),r(e(n),t,Zt,Wt,o,{},[-1,-1]),n}return i}();export default tn;export{Jt as preload};