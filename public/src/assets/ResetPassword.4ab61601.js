import{_ as g,u as w,B as b,f as s,r as n,o as y,c as v,w as r,an as o,g as c,t as m,bw as _}from"./index.24c8b4bf.js";const h={setup(){const a=w({loading:!1,data:{token:null,email:"",password:"",password_confirmation:""}});b(()=>{var t,e;a.value.data.token=((t=s.router.currentRoute.value.params)==null?void 0:t.token)||null,a.value.data.email=((e=s.router.currentRoute.value.query)==null?void 0:e.email)||null,(!a.value.data.token||!a.value.data.email)&&s.router.push({name:"auth.login"})});async function l(){a.value.loading=!0,s.clearErrors();const t=await s.store.dispatch("user/reset",a.value.data);t.status?(s.router.push({name:"auth.login"}),s.$q.notify({type:"positive",message:$t("pages.auth.reset_pass.success")})):t.statusCode==422&&s.setErrors(t.errors),a.value.loading=!1}return{state:a,send:l}}},k={class:"text-h5"},V={class:"text-subtitle text-center"};function C(a,l,t,e,K,q){const d=n("ap-card-section"),i=n("ap-input"),p=n("ap-button"),f=n("ap-card");return y(),v(f,{flat:""},{default:r(()=>[o(d,{class:"row justify-center q-gap-sm"},{default:r(()=>[c("span",k,m(a.$t("pages.auth.reset_pass.title")),1),c("span",V,m(a.$t("pages.auth.reset_pass.subtitle")),1)]),_:1}),o(d,{class:"column q-gap-md"},{default:r(()=>[o(i,{modelValue:e.state.data.password,"onUpdate:modelValue":l[0]||(l[0]=u=>e.state.data.password=u),type:"password",label:a.$t("globals.user_fields.password"),validate:"password",onKeypress:_(e.send,["enter"])},null,8,["modelValue","label","onKeypress"]),o(i,{modelValue:e.state.data.password_confirmation,"onUpdate:modelValue":l[1]||(l[1]=u=>e.state.data.password_confirmation=u),type:"password",label:a.$t("globals.user_fields.password_confirm"),validate:"globals.user_fields.password_confirm",onKeypress:_(e.send,["enter"])},null,8,["modelValue","label","onKeypress"])]),_:1}),o(d,{class:"row items-center justify-between"},{default:r(()=>[o(p,{flat:"",label:a.$t("globals.back"),to:{name:"auth.login"}},null,8,["label","to"]),o(p,{color:"primary",label:a.$t("pages.auth.reset_pass.reset"),loading:e.state.loading,onClick:e.send},null,8,["label","loading","onClick"])]),_:1})]),_:1})}var $=g(h,[["render",C]]);export{$ as default};