import{bf as Se,y as C,Y as Ee,a0 as J,a5 as re,P as Z,A as W,B as Te,N as K,x as Q,O as Ce,aV as $,bg as ee,ak as He,bh as Me,p as ce,G as ke,U as qe,v as We,H as Pe,b as E,W as Be,I as Le,J as Re,K as Ae,L as Oe,M as je,bi as ze,an as Fe,bj as Ve,bk as te,R as $e,q as T,s as De,T as Ke,bl as Qe,a6 as _e,bm as Ie,bn as Ye,S as Ne,aY as Ue,aE as Xe,k as ne}from"./index.efdacdc2.js";function Ge(){if(window.getSelection!==void 0){const e=window.getSelection();e.empty!==void 0?e.empty():e.removeAllRanges!==void 0&&(e.removeAllRanges(),Se.is.mobile!==!0&&e.addRange(document.createRange()))}else document.selection!==void 0&&document.selection.empty()}const Je={target:{default:!0},noParentEvent:Boolean,contextMenu:Boolean};function Ze({showing:e,avoidEmit:n,configureAnchorEl:l}){const{props:t,proxy:o,emit:r}=Q(),i=C(null);let u=null;function v(a){return i.value===null?!1:a===void 0||a.touches===void 0||a.touches.length<=1}const f={};l===void 0&&(Object.assign(f,{hide(a){o.hide(a)},toggle(a){o.toggle(a),a.qAnchorHandled=!0},toggleKey(a){Ee(a,13)===!0&&f.toggle(a)},contextClick(a){o.hide(a),J(a),re(()=>{o.show(a),a.qAnchorHandled=!0})},prevent:J,mobileTouch(a){if(f.mobileCleanup(a),v(a)!==!0)return;o.hide(a),i.value.classList.add("non-selectable");const c=a.target;Z(f,"anchor",[[c,"touchmove","mobileCleanup","passive"],[c,"touchend","mobileCleanup","passive"],[c,"touchcancel","mobileCleanup","passive"],[i.value,"contextmenu","prevent","notPassive"]]),u=setTimeout(()=>{u=null,o.show(a),a.qAnchorHandled=!0},300)},mobileCleanup(a){i.value.classList.remove("non-selectable"),u!==null&&(clearTimeout(u),u=null),e.value===!0&&a!==void 0&&Ge()}}),l=function(a=t.contextMenu){if(t.noParentEvent===!0||i.value===null)return;let c;a===!0?o.$q.platform.is.mobile===!0?c=[[i.value,"touchstart","mobileTouch","passive"]]:c=[[i.value,"mousedown","hide","passive"],[i.value,"contextmenu","contextClick","notPassive"]]:c=[[i.value,"click","toggle","passive"],[i.value,"keyup","toggleKey","passive"]],Z(f,"anchor",c)});function d(){Ce(f,"anchor")}function m(a){for(i.value=a;i.value.classList.contains("q-anchor--skip");)i.value=i.value.parentNode;l()}function b(){if(t.target===!1||t.target===""||o.$el.parentNode===null)i.value=null;else if(t.target===!0)m(o.$el.parentNode);else{let a=t.target;if(typeof t.target=="string")try{a=document.querySelector(t.target)}catch{a=void 0}a!=null?(i.value=a.$el||a,l()):(i.value=null,console.error(`Anchor: target "${t.target}" not found`))}}return W(()=>t.contextMenu,a=>{i.value!==null&&(d(),l(a))}),W(()=>t.target,()=>{i.value!==null&&d(),b()}),W(()=>t.noParentEvent,a=>{i.value!==null&&(a===!0?d():l())}),Te(()=>{b(),n!==!0&&t.modelValue===!0&&i.value===null&&r("update:modelValue",!1)}),K(()=>{u!==null&&clearTimeout(u),d()}),{anchorEl:i,canShow:v,anchorEvents:f}}function et(e,n){const l=C(null);let t;function o(u,v){const f=`${v!==void 0?"add":"remove"}EventListener`,d=v!==void 0?v:t;u!==window&&u[f]("scroll",d,$.passive),window[f]("scroll",d,$.passive),t=v}function r(){l.value!==null&&(o(l.value),l.value=null)}const i=W(()=>e.noParentEvent,()=>{l.value!==null&&(r(),n())});return K(i),{localScrollTarget:l,unconfigureScrollTarget:r,changeScrollEvent:o}}const{notPassiveCapture:R}=$,H=[];function A(e){const n=e.target;if(n===void 0||n.nodeType===8||n.classList.contains("no-pointer-events")===!0)return;let l=ee.length-1;for(;l>=0;){const t=ee[l].$;if(t.type.name==="QTooltip"){l--;continue}if(t.type.name!=="QDialog")break;if(t.props.seamless!==!0)return;l--}for(let t=H.length-1;t>=0;t--){const o=H[t];if((o.anchorEl.value===null||o.anchorEl.value.contains(n)===!1)&&(n===document.body||o.innerRef.value!==null&&o.innerRef.value.contains(n)===!1))e.qClickOutside=!0,o.onClickOutside(e);else return}}function tt(e){H.push(e),H.length===1&&(document.addEventListener("mousedown",A,R),document.addEventListener("touchstart",A,R))}function le(e){const n=H.findIndex(l=>l===e);n>-1&&(H.splice(n,1),H.length===0&&(document.removeEventListener("mousedown",A,R),document.removeEventListener("touchstart",A,R)))}let oe,ie;function ae(e){const n=e.split(" ");return n.length!==2?!1:["top","center","bottom"].includes(n[0])!==!0?(console.error("Anchor/Self position must start with one of top/center/bottom"),!1):["left","middle","right","start","end"].includes(n[1])!==!0?(console.error("Anchor/Self position must end with one of left/middle/right/start/end"),!1):!0}function nt(e){return e?!(e.length!==2||typeof e[0]!="number"||typeof e[1]!="number"):!0}const D={"start#ltr":"left","start#rtl":"right","end#ltr":"right","end#rtl":"left"};["left","middle","right"].forEach(e=>{D[`${e}#ltr`]=e,D[`${e}#rtl`]=e});function ue(e,n){const l=e.split(" ");return{vertical:l[0],horizontal:D[`${l[1]}#${n===!0?"rtl":"ltr"}`]}}function lt(e,n){let{top:l,left:t,right:o,bottom:r,width:i,height:u}=e.getBoundingClientRect();return n!==void 0&&(l-=n[1],t-=n[0],r+=n[1],o+=n[0],i+=n[0],u+=n[1]),{top:l,bottom:r,height:u,left:t,right:o,width:i,middle:t+(o-t)/2,center:l+(r-l)/2}}function ot(e,n,l){let{top:t,left:o}=e.getBoundingClientRect();return t+=n.top,o+=n.left,l!==void 0&&(t+=l[1],o+=l[0]),{top:t,bottom:t+1,height:1,left:o,right:o+1,width:1,middle:o,center:t}}function it(e,n){return{top:0,center:n/2,bottom:n,left:0,middle:e/2,right:e}}function se(e,n,l,t){return{top:e[l.vertical]-n[t.vertical],left:e[l.horizontal]-n[t.horizontal]}}function fe(e,n=0){if(e.targetEl===null||e.anchorEl===null||n>5)return;if(e.targetEl.offsetHeight===0||e.targetEl.offsetWidth===0){setTimeout(()=>{fe(e,n+1)},10);return}const{targetEl:l,offset:t,anchorEl:o,anchorOrigin:r,selfOrigin:i,absoluteOffset:u,fit:v,cover:f,maxHeight:d,maxWidth:m}=e;if(He.is.ios===!0&&window.visualViewport!==void 0){const q=document.body.style,{offsetLeft:y,offsetTop:p}=window.visualViewport;y!==oe&&(q.setProperty("--q-pe-left",y+"px"),oe=y),p!==ie&&(q.setProperty("--q-pe-top",p+"px"),ie=p)}const{scrollLeft:b,scrollTop:a}=l,c=u===void 0?lt(o,f===!0?[0,0]:t):ot(o,u,t);Object.assign(l.style,{top:0,left:0,minWidth:null,minHeight:null,maxWidth:m||"100vw",maxHeight:d||"100vh",visibility:"visible"});const{offsetWidth:M,offsetHeight:S}=l,{elWidth:k,elHeight:P}=v===!0||f===!0?{elWidth:Math.max(c.width,M),elHeight:f===!0?Math.max(c.height,S):S}:{elWidth:M,elHeight:S};let x={maxWidth:m,maxHeight:d};(v===!0||f===!0)&&(x.minWidth=c.width+"px",f===!0&&(x.minHeight=c.height+"px")),Object.assign(l.style,x);const h=it(k,P);let g=se(c,h,r,i);if(u===void 0||t===void 0)V(g,c,h,r,i);else{const{top:q,left:y}=g;V(g,c,h,r,i);let p=!1;if(g.top!==q){p=!0;const w=2*t[1];c.center=c.top-=w,c.bottom-=w+2}if(g.left!==y){p=!0;const w=2*t[0];c.middle=c.left-=w,c.right-=w+2}p===!0&&(g=se(c,h,r,i),V(g,c,h,r,i))}x={top:g.top+"px",left:g.left+"px"},g.maxHeight!==void 0&&(x.maxHeight=g.maxHeight+"px",c.height>g.maxHeight&&(x.minHeight=x.maxHeight)),g.maxWidth!==void 0&&(x.maxWidth=g.maxWidth+"px",c.width>g.maxWidth&&(x.minWidth=x.maxWidth)),Object.assign(l.style,x),l.scrollTop!==a&&(l.scrollTop=a),l.scrollLeft!==b&&(l.scrollLeft=b)}function V(e,n,l,t,o){const r=l.bottom,i=l.right,u=Me(),v=window.innerHeight-u,f=document.body.clientWidth;if(e.top<0||e.top+r>v)if(o.vertical==="center")e.top=n[t.vertical]>v/2?Math.max(0,v-r):0,e.maxHeight=Math.min(r,v);else if(n[t.vertical]>v/2){const d=Math.min(v,t.vertical==="center"?n.center:t.vertical===o.vertical?n.bottom:n.top);e.maxHeight=Math.min(r,d),e.top=Math.max(0,d-r)}else e.top=Math.max(0,t.vertical==="center"?n.center:t.vertical===o.vertical?n.top:n.bottom),e.maxHeight=Math.min(r,v-e.top);if(e.left<0||e.left+i>f)if(e.maxWidth=Math.min(i,f),o.horizontal==="middle")e.left=n[t.horizontal]>f/2?Math.max(0,f-i):0;else if(n[t.horizontal]>f/2){const d=Math.min(f,t.horizontal==="middle"?n.middle:t.horizontal===o.horizontal?n.right:n.left);e.maxWidth=Math.min(i,d),e.left=Math.max(0,d-e.maxWidth)}else e.left=Math.max(0,t.horizontal==="middle"?n.middle:t.horizontal===o.horizontal?n.left:n.right),e.maxWidth=Math.min(i,f-e.left)}var at=ce({name:"QMenu",inheritAttrs:!1,props:{...Je,...ke,...qe,...We,persistent:Boolean,autoClose:Boolean,separateClosePopup:Boolean,noRouteDismiss:Boolean,noRefocus:Boolean,noFocus:Boolean,fit:Boolean,cover:Boolean,square:Boolean,anchor:{type:String,validator:ae},self:{type:String,validator:ae},offset:{type:Array,validator:nt},scrollTarget:{default:void 0},touchPosition:Boolean,maxHeight:{type:String,default:null},maxWidth:{type:String,default:null}},emits:[...Pe,"click","escapeKey"],setup(e,{slots:n,emit:l,attrs:t}){let o=null,r,i,u;const v=Q(),{proxy:f}=v,{$q:d}=f,m=C(null),b=C(!1),a=E(()=>e.persistent!==!0&&e.noRouteDismiss!==!0),c=Be(e,d),{registerTick:M,removeTick:S}=Le(),{registerTimeout:k}=Re(),{transitionProps:P,transitionStyle:x}=Ae(e),{localScrollTarget:h,changeScrollEvent:g,unconfigureScrollTarget:q}=et(e,X),{anchorEl:y,canShow:p}=Ze({showing:b}),{hide:w}=Oe({showing:b,canShow:p,handleShow:ge,handleHide:be,hideOnRouteChange:a,processOnMount:!0}),{showPortal:_,hidePortal:I,renderPortal:de}=je(v,m,ye,"menu"),O={anchorEl:y,innerRef:m,onClickOutside(s){if(e.persistent!==!0&&b.value===!0)return w(s),(s.type==="touchstart"||s.target.classList.contains("q-dialog__backdrop"))&&Ne(s),!0}},Y=E(()=>ue(e.anchor||(e.cover===!0?"center middle":"bottom start"),d.lang.rtl)),ve=E(()=>e.cover===!0?Y.value:ue(e.self||"top start",d.lang.rtl)),he=E(()=>(e.square===!0?" q-menu--square":"")+(c.value===!0?" q-menu--dark q-dark":"")),me=E(()=>e.autoClose===!0?{onClick:xe}:{}),N=E(()=>b.value===!0&&e.persistent!==!0);W(N,s=>{s===!0?(Qe(z),tt(O)):(te(z),le(O))});function j(){_e(()=>{let s=m.value;s&&s.contains(document.activeElement)!==!0&&(s=s.querySelector("[autofocus][tabindex], [data-autofocus][tabindex]")||s.querySelector("[autofocus] [tabindex], [data-autofocus] [tabindex]")||s.querySelector("[autofocus], [data-autofocus]")||s,s.focus({preventScroll:!0}))})}function ge(s){if(o=e.noRefocus===!1?document.activeElement:null,ze(G),_(),X(),r=void 0,s!==void 0&&(e.touchPosition||e.contextMenu)){const F=Fe(s);if(F.left!==void 0){const{top:pe,left:we}=y.value.getBoundingClientRect();r={left:F.left-we,top:F.top-pe}}}i===void 0&&(i=W(()=>d.screen.width+"|"+d.screen.height+"|"+e.self+"|"+e.anchor+"|"+d.lang.rtl,B)),e.noFocus!==!0&&document.activeElement.blur(),M(()=>{B(),e.noFocus!==!0&&j()}),k(()=>{d.platform.is.ios===!0&&(u=e.autoClose,m.value.click()),B(),_(!0),l("show",s)},e.transitionDuration)}function be(s){S(),I(),U(!0),o!==null&&(s===void 0||s.qClickOutside!==!0)&&(((s&&s.type.indexOf("key")===0?o.closest('[tabindex]:not([tabindex^="-"])'):void 0)||o).focus(),o=null),k(()=>{I(!0),l("hide",s)},e.transitionDuration)}function U(s){r=void 0,i!==void 0&&(i(),i=void 0),(s===!0||b.value===!0)&&(Ve(G),q(),le(O),te(z)),s!==!0&&(o=null)}function X(){(y.value!==null||e.scrollTarget!==void 0)&&(h.value=$e(y.value,e.scrollTarget),g(h.value,B))}function xe(s){u!==!0?(Ie(f,s),l("click",s)):u=!1}function G(s){N.value===!0&&e.noFocus!==!0&&Ye(m.value,s.target)!==!0&&j()}function z(s){l("escapeKey"),w(s)}function B(){fe({targetEl:m.value,offset:e.offset,anchorEl:y.value,anchorOrigin:Y.value,selfOrigin:ve.value,absoluteOffset:r,fit:e.fit,cover:e.cover,maxHeight:e.maxHeight,maxWidth:e.maxWidth})}function ye(){return T(Ke,P.value,()=>b.value===!0?T("div",{role:"menu",...t,ref:m,tabindex:-1,class:["q-menu q-position-engine scroll"+he.value,t.class],style:[t.style,x.value],...me.value},De(n.default)):null)}return K(U),Object.assign(f,{focus:j,updatePosition:B}),de}});function L(e,n=new WeakMap){if(Object(e)!==e)return e;if(n.has(e))return n.get(e);const l=e instanceof Date?new Date(e):e instanceof RegExp?new RegExp(e.source,e.flags):e instanceof Set?new Set:e instanceof Map?new Map:typeof e.constructor!="function"?Object.create(null):e.prototype!==void 0&&typeof e.prototype.constructor=="function"?e:new e.constructor;if(typeof e.constructor=="function"&&typeof e.valueOf=="function"){const t=e.valueOf();if(Object(t)!==t){const o=new e.constructor(t);return n.set(e,o),o}}return n.set(e,l),e instanceof Set?e.forEach(t=>{l.add(L(t,n))}):e instanceof Map&&e.forEach((t,o)=>{l.set(o,L(t,n))}),Object.assign(l,...Object.keys(e).map(t=>({[t]:L(e[t],n)})))}var st=ce({name:"QPopupEdit",props:{modelValue:{required:!0},title:String,buttons:Boolean,labelSet:String,labelCancel:String,color:{type:String,default:"primary"},validate:{type:Function,default:()=>!0},autoSave:Boolean,cover:{type:Boolean,default:!0},disable:Boolean},emits:["update:modelValue","save","cancel","beforeShow","show","beforeHide","hide"],setup(e,{slots:n,emit:l}){const{proxy:t}=Q(),{$q:o}=t,r=C(null),i=C(""),u=C("");let v=!1;const f=E(()=>Ue({initialValue:i.value,validate:e.validate,set:d,cancel:m,updatePosition:b},"value",()=>u.value,h=>{u.value=h}));function d(){e.validate(u.value)!==!1&&(a()===!0&&(l("save",u.value,i.value),l("update:modelValue",u.value)),c())}function m(){a()===!0&&l("cancel",u.value,i.value),c()}function b(){re(()=>{r.value.updatePosition()})}function a(){return Xe(u.value,i.value)===!1}function c(){v=!0,r.value.hide()}function M(){v=!1,i.value=L(e.modelValue),u.value=L(e.modelValue),l("beforeShow")}function S(){l("show")}function k(){v===!1&&a()===!0&&(e.autoSave===!0&&e.validate(u.value)===!0?(l("save",u.value,i.value),l("update:modelValue",u.value)):l("cancel",u.value,i.value)),l("beforeHide")}function P(){l("hide")}function x(){const h=n.default!==void 0?[].concat(n.default(f.value)):[];return e.title&&h.unshift(T("div",{class:"q-dialog__title q-mt-sm q-mb-sm"},e.title)),e.buttons===!0&&h.push(T("div",{class:"q-popup-edit__buttons row justify-center no-wrap"},[T(ne,{flat:!0,color:e.color,label:e.labelCancel||o.lang.label.cancel,onClick:m}),T(ne,{flat:!0,color:e.color,label:e.labelSet||o.lang.label.set,onClick:d})])),h}return Object.assign(t,{set:d,cancel:m,show(h){r.value!==null&&r.value.show(h)},hide(h){r.value!==null&&r.value.hide(h)},updatePosition:b}),()=>{if(e.disable!==!0)return T(at,{ref:r,class:"q-popup-edit",cover:e.cover,onBeforeShow:M,onShow:S,onBeforeHide:k,onHide:P,onEscapeKey:m},x)}}});export{at as Q,nt as a,et as b,Ze as c,Ge as d,tt as e,st as f,ue as p,le as r,fe as s,Je as u,ae as v};
