import{bt as i,f as r}from"./index.06703071.js";var n=i(async({app:a})=>{a.directive("password",{created:(o,s)=>{o.addEventListener("click",function(e){if(r.state.user.is_logged_in){let t=s.value;if(t)return r.store.commit("app/password_popup",{open:!0,fn:t}),e.preventDefault(),e.stopPropagation(),!1}})}})});export{n as default};
