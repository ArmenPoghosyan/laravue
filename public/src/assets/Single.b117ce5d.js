import{f as P}from"./QPopupEdit.d04fa74b.js";import{C}from"./ClosePopup.fbcc733e.js";import{_ as j,y as q,r as o,o as c,d as k,ab as t,w as u,g as l,t as r,aN as v,aR as $,c as f,f as d,B as R,n as N,a_ as S}from"./index.de5baf35.js";const O={emits:["update:modelValue"],props:{modelValue:{type:Object,default:()=>({})}},setup(e,{emit:s}){const i=q(e.modelValue);q([]);const a=q({open:!1,loading:!1,data:{id:null,type:null,first_name:null,last_name:null,phone:null,birth_date:null}});function n(){a.value.open=!0,a.value.data={id:i.value.id,type:i.value.type,first_name:i.value.first_name,last_name:i.value.last_name,phone:i.value.phone,birth_date:i.value.birth_date}}async function p(){a.value.loading=!0,d.clearErrors();const _=await d.store.dispatch("users/update",a.value.data);_.status?(a.value.open=!1,d.$q.notify({type:"positive",message:d.locale.t("globals.saved")}),i.value={...i.value,..._.user||{}},s("update:modelValue",i.value)):_.statusCode==422?d.setErrors(_.errors):d.$q.notify({type:"negative",message:d.locale.t("globals.errors.unknown")}),a.value.loading=!1}return{user:i,editing:a,open_edit:n,save:p}}},Q={class:"column full-width"},A={key:0,class:"row q-pa-md justify-end"},F={class:"text-h6 q-mr-auto"};function G(e,s,i,a,n,p){const _=o("ap-markup-table"),b=o("ap-button"),U=o("ap-dialog-header"),y=o("ap-select"),h=o("ap-input"),V=o("ap-date-picker"),w=o("ap-card-section"),z=o("ap-dialog-card"),E=o("ap-dialog");return c(),k("div",Q,[t(_,{flat:""},{default:u(()=>[l("tbody",null,[l("tr",null,[l("td",null,r(e.$t("globals.user_fields.first_name")),1),l("td",null,r(a.user.first_name),1)]),l("tr",null,[l("td",null,r(e.$t("globals.user_fields.last_name")),1),l("td",null,r(a.user.last_name),1)]),l("tr",null,[l("td",null,r(e.$t("globals.user_fields.phone")),1),l("td",null,r(a.user.phone||"-"),1)]),l("tr",null,[l("td",null,r(e.$t("globals.user_fields.email")),1),l("td",null,r(a.user.email),1)]),l("tr",null,[l("td",null,r(e.$t("globals.user_fields.type")),1),l("td",null,r(e.$t(`globals.user_types.${a.user.type}`)),1)]),l("tr",null,[l("td",null,r(e.$t("globals.user_fields.age")),1),l("td",null,r(a.user.age),1)]),l("tr",null,[l("td",null,r(e.$t("globals.user_fields.birth_date")),1),l("td",null,r(a.user.birth_date?e.nice_date(a.user.birth_date):"-"),1)])])]),_:1}),a.user.type=="admin"?(c(),k("div",A,[t(b,{outline:"",icon:"edit",label:e.$t("globals.edit"),color:"primary",onClick:a.open_edit},null,8,["label","onClick"])])):v("",!0),t(E,{modelValue:a.editing.open,"onUpdate:modelValue":s[5]||(s[5]=m=>a.editing.open=m)},{default:u(()=>[t(z,null,{default:u(()=>[t(U,null,{default:u(()=>[l("span",F,r(e.$t("globals.edit")),1),$(t(b,{round:"",flat:"",icon:"close",size:"12px"},null,512),[[C]])]),_:1}),t(w,{class:"row q-col-gutter-md"},{default:u(()=>[e.$user.type=="admin"?(c(),f(y,{key:0,class:"col-xs-12",label:e.$t("globals.user_fields.type"),options:e.lists.user_types.value,modelValue:a.editing.data.type,"onUpdate:modelValue":s[0]||(s[0]=m=>a.editing.data.type=m),"map-options":"","emit-value":"",validate:"type"},null,8,["label","options","modelValue"])):v("",!0),t(h,{modelValue:a.editing.data.first_name,"onUpdate:modelValue":s[1]||(s[1]=m=>a.editing.data.first_name=m),class:"col-xs-12 col-sm-6",label:e.$t("globals.user_fields.first_name"),validate:"first_name"},null,8,["modelValue","label"]),t(h,{modelValue:a.editing.data.last_name,"onUpdate:modelValue":s[2]||(s[2]=m=>a.editing.data.last_name=m),class:"col-xs-12 col-sm-6",label:e.$t("globals.user_fields.last_name"),validate:"last_name"},null,8,["modelValue","label"]),t(h,{modelValue:a.editing.data.phone,"onUpdate:modelValue":s[3]||(s[3]=m=>a.editing.data.phone=m),class:"col-xs-12 col-sm-6",label:e.$t("globals.user_fields.phone"),validate:"phone"},null,8,["modelValue","label"]),t(V,{modelValue:a.editing.data.birth_date,"onUpdate:modelValue":s[4]||(s[4]=m=>a.editing.data.birth_date=m),class:"col-xs-12 col-sm-6",label:e.$t("globals.user_fields.birth_date"),validate:"birth_date"},null,8,["modelValue","label"])]),_:1}),t(w,{class:"row justify-between"},{default:u(()=>[$(t(b,{flat:"",label:e.$t("globals.cancel")},null,8,["label"]),[[C]]),t(b,{color:"primary",label:e.$t("globals.continue"),loading:e.state.loading,onClick:a.save},null,8,["label","loading","onClick"])]),_:1})]),_:1})]),_:1},8,["modelValue"])])}var H=j(O,[["render",G]]);const J={components:{Info:H},setup(){const e=q({is_adding:!1,loaded:!1,loading:!1,disabled:!1,status_loading:!1,page:"info",user:{id:null}});R(()=>{var p;const n=(p=d.router.currentRoute.value.params)==null?void 0:p.id;n&&s(n)});async function s(n){e.value.loading=!0;const p=await d.store.dispatch("users/show",{id:n});p.status?e.value.user=p.user:d.$q.notify({color:"negative",message:d.locale.t("globals.errors.unknown")}),e.value.loaded=!0,e.value.loading=!1}async function i(){var p,_;e.value.loading=!0,d.clearErrors();let n=null;e.value.is_adding?n=await d.store.dispatch(`${base_store}/store`,e.value.data):n=await d.store.dispatch(`${base_store}/update`,e.value.data),n.status?done():n.statusCode==422?((_=(p=n.errors)==null?void 0:p.content)!=null&&_.length&&d.$q.notify({color:"negative",message:n.errors.content[0]}),d.setErrors(n.errors)):d.$q.notify({color:"negative",message:d.locale.t("globals.errors.unknown")}),e.value.loading=!1}async function a(n){e.value.status_loading=!0,(await d.store.dispatch("users/update_status",{id:e.value.user.id,status:n})).status&&(e.value.user.status=n),e.value.status_loading=!1}return{state:e,save:i,update_status:a}}},K={class:"column items-center no-wrap text-center"},L={class:"text-h6"},T=l("div",{class:"row no-wrap q-gap-lg"},null,-1),W={key:0,class:"row items-center q-gap-sm text-weight-bold cursor-pointer"},X={key:0,class:"text-positive"},Y={key:1,class:"text-negative"},Z={class:"column q-gap-md"},x={class:"row items-center q-gap-md"};function ee(e,s,i,a,n,p){const _=o("ap-img"),b=o("ap-icon"),U=o("ap-avatar"),y=o("ap-button"),h=o("ap-select"),V=o("ap-card-section"),w=o("ap-route-tab"),z=o("ap-tabs"),E=o("ap-separator"),m=o("Info"),I=o("ap-card"),D=o("ap-page-loader"),M=o("ap-page");return c(),f(M,null,{default:u(()=>[a.state.loaded?(c(),f(I,{key:0},{default:u(()=>[t(V,{horizontal:e.$q.screen.gt.xs,"no-padding":"",class:"q-gap-lg items-start overflow-hidden"},{default:u(()=>[t(V,{class:N(["flex-shrink column flex-center q-my-lg q-gap-md",{"q-mx-xl q-pa-none":e.$q.screen.gt.xs,"q-px-sm":e.$q.screen.lt.sm}])},{default:u(()=>[t(U,{bordered:"",size:"100px"},{default:u(()=>[a.state.user.avatar?(c(),f(_,{key:0,src:e.media(a.state.user.avatar,"s"),fit:"cover",class:"fit cursor-pointer",onClick:s[0]||(s[0]=S(g=>e.open_media_popup(e.media(a.state.user.avatar)),["stop","prevent"]))},null,8,["src"])):(c(),f(b,{key:1,name:"sym_o_person",color:"grey-6"}))]),_:1}),l("div",K,[l("span",L,r(a.state.user.full_name),1)]),T,a.state.user.type!="owner"&&a.state.user.id!=e.$user.id?(c(),k("span",W,[t(y,{round:"",size:"xs",icon:"sym_o_edit"}),a.state.user.status=="active"?(c(),k("span",X,r(e.$t("globals.active")),1)):v("",!0),a.state.user.status=="archived"?(c(),k("span",Y,r(e.$t("globals.archived")),1)):v("",!0),t(P,{modelValue:a.state.user.status,"onUpdate:modelValue":s[1]||(s[1]=g=>a.state.user.status=g),class:"q-pa-md"},{default:u(g=>[l("div",Z,[t(h,{modelValue:g.value,"onUpdate:modelValue":B=>g.value=B,options:e.lists.user_statuses.value,"map-options":"","emit-value":"",label:e.$t("globals.status"),style:{"min-width":"200px"}},null,8,["modelValue","onUpdate:modelValue","options","label"]),l("div",x,[$(t(y,{flat:"",label:e.$t("globals.close"),class:"q-mr-auto"},null,8,["label"]),[[C]]),$(t(y,{color:"primary",label:e.$t("globals.save"),loading:a.state.status_loading,onClick:B=>a.update_status(g.value)},null,8,["label","loading","onClick"]),[[C]])])])]),_:1},8,["modelValue"])])):v("",!0)]),_:1},8,["class"]),t(V,{class:"q-py-none full-width overflow-hidden","no-padding":""},{default:u(()=>[t(z,{"outside-arrows":"","mouse-scroll":"",modelValue:a.state.page,"onUpdate:modelValue":s[2]||(s[2]=g=>a.state.page=g),"inline-label":"","active-color":"primary",align:"left"},{default:u(()=>[t(w,{to:{name:"user.index"},name:"info",icon:"sym_o_person",label:e.$t("pages.users.info")},null,8,["to","label"]),t(w,{to:{name:"user.websites"},name:"websites",icon:"sym_o_language",label:e.$t("pages.websites.title")},null,8,["to","label"])]),_:1},8,["modelValue"]),t(E),l("div",{class:N(["column full-width",{"q-py-sm":e.$q.screen.gt.xs,"q-pa-md":e.$q.screen.lt.sm}])},[a.state.page=="info"?(c(),f(m,{key:0,modelValue:a.state.user,"onUpdate:modelValue":s[3]||(s[3]=g=>a.state.user=g)},null,8,["modelValue"])):v("",!0)],2)]),_:1})]),_:1},8,["horizontal"])]),_:1})):(c(),f(D,{key:1}))]),_:1})}var se=j(J,[["render",ee]]);export{se as default};
