import{_ as y,p as h,r as l,c as i,w as n,b2 as d,o as t,ai as r,bg as k,aT as p,d as _,aU as f,bw as w}from"./index.02061857.js";const v={setup(){const e=h({loading:!1,sent:!1,data:{email:""}});async function s(){e.value.loading=!0,d.clearErrors();const o=await d.store.dispatch("user/forgot",e.value.data);o.status?e.value.sent=!0:o.statusCode==422&&d.setErrors(o.errors),e.value.loading=!1}return{state:e,send:s}}},C={class:"text-h5"},V={key:0,class:"text-subtitle text-center"},x={key:0,class:"flex flex-center q-pa-lg text-center"};function B(e,s,o,a,E,K){const c=l("ap-card-section"),m=l("ap-input"),u=l("ap-button"),g=l("ap-card");return t(),i(g,{flat:""},{default:n(()=>[r(c,{class:"row justify-center q-gap-sm"},{default:n(()=>[k("span",C,p(e.$t("pages.auth.forgot_pass.title")),1),a.state.sent?f("",!0):(t(),_("span",V,p(e.$t("pages.auth.forgot_pass.subtitle")),1))]),_:1}),r(c,{class:"column"},{default:n(()=>[a.state.sent?(t(),_("span",x,p(e.$t("pages.auth.forgot_pass.sent_notice")),1)):(t(),i(m,{key:1,modelValue:a.state.data.email,"onUpdate:modelValue":s[0]||(s[0]=b=>a.state.data.email=b),type:"email",label:e.$t("globals.user_fields.email"),validate:"email",onKeypress:w(a.send,["enter"])},null,8,["modelValue","label","onKeypress"]))]),_:1}),r(c,{class:"row items-center justify-between"},{default:n(()=>[r(u,{flat:"",label:e.$t("globals.back"),to:{name:"auth.login"}},null,8,["label","to"]),a.state.sent?f("",!0):(t(),i(u,{key:0,color:"primary",label:e.$t("pages.auth.forgot_pass.send"),loading:a.state.loading,onClick:a.send},null,8,["label","loading","onClick"]))]),_:1})]),_:1})}var j=y(v,[["render",B]]);export{j as default};