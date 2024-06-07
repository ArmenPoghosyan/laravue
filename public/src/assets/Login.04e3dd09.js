import{_ as y,y as V,r as n,o as u,c as f,w as s,f as i,bv as h,ab as t,g as c,t as m,aN as k,T as C,bw as g,aZ as K}from"./index.efdacdc2.js";const N={setup(){const a=V({loading:!1,validation:null,data:{email:"",password:"",remember:!0}});async function o(){a.value.loading=!0,a.value.validation=null,i.clearErrors();const l=await i.store.dispatch("user/login",a.value.data);l.status?i.router.push({name:h.app.home}):l.statusCode==422&&(l.errors?i.setErrors(l.errors):a.value.validation=l==null?void 0:l.message),a.value.loading=!1}return{state:a,login:o}}},q={class:"text-h5"},x={class:"full-width rounded-12 bg-negative text-white q-pa-md"},B={class:"row items-center q-gap-md"};function T(a,o,l,e,U,j){const d=n("ap-card-section"),p=n("ap-input"),_=n("ap-checkbox"),b=n("ap-link"),v=n("ap-button"),w=n("ap-card");return u(),f(w,{flat:""},{default:s(()=>[t(d,{class:"row justify-center"},{default:s(()=>[c("span",q,m(a.config.app.name),1)]),_:1}),t(C,{"enter-active-class":"animated fadeIn","leave-active-class":"animated fadeOut"},{default:s(()=>[e.state.validation!=null?(u(),f(d,{key:0},{default:s(()=>[c("div",x,m(e.state.validation),1)]),_:1})):k("",!0)]),_:1}),t(d,{class:"column q-gap-md"},{default:s(()=>[t(p,{modelValue:e.state.data.email,"onUpdate:modelValue":o[0]||(o[0]=r=>e.state.data.email=r),type:"email",label:a.$t("globals.user_fields.email"),validate:"email",onKeypress:g(e.login,["enter"])},null,8,["modelValue","label","onKeypress"]),t(p,{modelValue:e.state.data.password,"onUpdate:modelValue":o[1]||(o[1]=r=>e.state.data.password=r),type:"password",label:a.$t("globals.user_fields.password"),validate:"password",onKeypress:g(e.login,["enter"])},null,8,["modelValue","label","onKeypress"]),c("div",B,[t(_,{dense:"",modelValue:e.state.data.remember,"onUpdate:modelValue":o[2]||(o[2]=r=>e.state.data.remember=r),label:a.$t("pages.auth.login.remember"),class:"q-mr-auto"},null,8,["modelValue","label"]),t(b,{to:{name:"auth.forgot"}},{default:s(()=>[K(m(a.$t("pages.auth.login.forgot_password")),1)]),_:1},8,["to"])])]),_:1}),t(d,{class:"row items-center justify-end"},{default:s(()=>[t(v,{color:"primary",label:a.$t("pages.auth.login.login"),loading:e.state.loading,onClick:e.login},null,8,["label","loading","onClick"])]),_:1})]),_:1})}var D=y(N,[["render",T]]);export{D as default};