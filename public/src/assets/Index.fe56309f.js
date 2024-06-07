import{C}from"./ClosePopup.7b950a27.js";import{_ as M,f as i,y as q,r as n,o as v,c as y,w as s,ab as t,g as w,t as u,aN as U,n as z,a_ as Z,aZ as _,aR as N}from"./index.efdacdc2.js";import"./moment.9709ab41.js";const F={setup(){const o=i.paginator(20),a=q({list:[],filters:{query:"",type:null,status:null,date:{from:null,to:null}}}),p=q({open:!1,loading:!1,data:{email:"",phone:"",type:"user",birth_date:"",first_name:"",last_name:""}}),l=[{name:"avatar",align:"left",label:i.locale.t("globals.multimedia_types.photo"),sortable:!1,style:"width: 60px",headerStyle:"width: 60px"},{name:"first_name",align:"left",label:i.locale.t("globals.user_fields.first_name"),sortable:!0},{name:"last_name",align:"left",label:i.locale.t("globals.user_fields.last_name"),sortable:!0},{name:"birth_date",align:"left",label:i.locale.t("globals.user_fields.age"),sortable:!0},{name:"phone",align:"left",label:i.locale.t("globals.user_fields.phone"),sortable:!0},{name:"email",align:"left",label:i.locale.t("globals.user_fields.email"),sortable:!0},{name:"type",align:"left",label:i.locale.t("globals.user_fields.type"),sortable:!0},{name:"deleted_at",align:"left",label:i.locale.t("globals.status"),sortable:!0},{name:"created_at",align:"left",label:i.locale.t("globals.user_fields.registration_date"),sortable:!0}];async function V(r=null){var f,b;a.value.loading=!0,r||(r={pagination:o.value.reset()});const d=await i.store.dispatch("users/index",{...r.pagination.normalize(),filters:a.value.filters,all:!0});d.status&&(a.value.list=((f=d==null?void 0:d.users)==null?void 0:f.data)||[],r.pagination.setTotal(((b=d==null?void 0:d.users)==null?void 0:b.total)||0)),o.value.fromProps(r.pagination),a.value.loading=!1}function k(){p.value.data={email:"",type:"user",first_name:"",last_name:""}}function g(){k(),p.value.open=!0}async function c(){p.value.loading=!0,i.clearErrors();const r=await i.store.dispatch("users/store",p.value.data);r.status?(V(),p.value.open=!1):r.statusCode==422?i.setErrors(r.errors):i.$q.notify({type:"negative",message:i.locale.t("globals.errors.unknown")}),p.value.loading=!1}return{state:a,pagination:o,invite:p,columns:l,fetch:V,open_invite:g,invite_user:c}}},G={class:"text-h6 q-mr-auto"},H={class:"row items-center q-col-gutter-md"},J={class:"text-h6 q-mr-auto"};function K(o,a,p,l,V,k){const g=n("ap-button"),c=n("ap-card-section"),r=n("ap-separator"),d=n("ap-icon"),f=n("ap-input"),b=n("ap-select"),h=n("ap-date-picker"),B=n("ap-img"),R=n("ap-avatar"),m=n("ap-td"),A=n("ap-tr"),D=n("ap-table"),E=n("ap-card"),P=n("ap-dialog-header"),S=n("ap-dialog-card"),T=n("ap-dialog"),j=n("ap-page");return v(),y(j,null,{default:s(()=>[t(E,null,{default:s(()=>[t(c,{class:"row items-center q-gap-md"},{default:s(()=>{var e;return[w("span",G,u(o.$t("pages.users.title")),1),((e=o.$user)==null?void 0:e.type)=="admin"?(v(),y(g,{key:0,"hide-label-sm":"",outline:"",color:"primary",icon:"add",label:o.$t("globals.add"),onClick:l.open_invite},null,8,["label","onClick"])):U("",!0)]}),_:1}),t(r),t(c,null,{default:s(()=>[w("div",H,[t(f,{modelValue:l.state.filters.query,"onUpdate:modelValue":[a[0]||(a[0]=e=>l.state.filters.query=e),a[1]||(a[1]=e=>l.fetch())],"no-padding-right":"",debounce:"300",class:z([{"full-width":o.$q.screen.lt.sm},"col-xs-12 col-sm-6 col-md-3 col-lg-2"])},{prepend:s(()=>[t(d,{name:"sym_o_search",size:"24px"})]),_:1},8,["modelValue","class"]),t(b,{modelValue:l.state.filters.type,"onUpdate:modelValue":[a[2]||(a[2]=e=>l.state.filters.type=e),a[3]||(a[3]=e=>l.fetch())],"map-options":"","emit-value":"",options:o.lists.prependAll(o.lists.user_types.value),label:o.$t("globals.user_fields.type"),class:"col-xs-12 col-sm-6 col-md-2 col-lg-1"},null,8,["modelValue","options","label"]),t(b,{modelValue:l.state.filters.status,"onUpdate:modelValue":[a[4]||(a[4]=e=>l.state.filters.status=e),a[5]||(a[5]=e=>l.fetch())],options:o.lists.prependAll(o.lists.user_statuses.value),"map-options":"","emit-value":"",label:o.$t("globals.status"),class:"col-xs-12 col-sm-6 col-md-2 col-lg-1"},null,8,["modelValue","options","label"]),t(h,{modelValue:l.state.filters.date,"onUpdate:modelValue":[a[6]||(a[6]=e=>l.state.filters.date=e),a[7]||(a[7]=e=>l.fetch())],label:o.$t("globals.user_fields.registration_date"),range:"",class:"col-xs-12 col-sm-6 col-md-3 col-lg-2"},null,8,["modelValue","label"])])]),_:1}),t(c,{"no-padding":""},{default:s(()=>[t(D,{name:"users","use-server":"",flat:"",columns:l.columns,rows:l.state.list,loading:l.state.loading,pagination:l.pagination,"onUpdate:pagination":a[8]||(a[8]=e=>l.pagination=e),onRequest:l.fetch},{body:s(e=>[t(A,{class:"cursor-pointer",onClick:I=>o.$router.push({name:"user.index",params:{id:e.row.id}})},{default:s(()=>[t(m,{props:e,key:"avatar"},{default:s(()=>[t(R,{bordered:"",rounded:"",size:"40px"},{default:s(()=>[e.row.avatar?(v(),y(B,{key:0,src:o.media(e.row.avatar,"s"),fit:"cover",class:"fit cursor-pointer",onClick:Z(I=>o.open_media_popup(o.media(e.row.avatar)),["stop","prevent"])},null,8,["src","onClick"])):(v(),y(d,{key:1,name:"sym_o_person",color:"grey-6"}))]),_:2},1024)]),_:2},1032,["props"]),t(m,{props:e,key:"first_name"},{default:s(()=>[_(u(e.row.first_name),1)]),_:2},1032,["props"]),t(m,{props:e,key:"last_name"},{default:s(()=>[_(u(e.row.last_name),1)]),_:2},1032,["props"]),t(m,{props:e,key:"birth_date"},{default:s(()=>[_(u(e.row.age),1)]),_:2},1032,["props"]),t(m,{props:e,key:"phone"},{default:s(()=>[_(u(e.row.phone||"-"),1)]),_:2},1032,["props"]),t(m,{props:e,key:"email"},{default:s(()=>[_(u(e.row.email),1)]),_:2},1032,["props"]),t(m,{props:e,key:"type"},{default:s(()=>[_(u(o.$t(`globals.user_types.${e.row.type}`)),1)]),_:2},1032,["props"]),t(m,{props:e,key:"deleted_at",class:z(["text-uppercase text-weight-bold",{"text-positive":e.row.status=="active","text-negative":e.row.status=="archived"}])},{default:s(()=>[_(u(e.row.status=="active"?o.$t("globals.active"):o.$t("globals.archived")),1)]),_:2},1032,["props","class"]),t(m,{props:e,key:"created_at"},{default:s(()=>[_(u(o.nice_date(e.row.created_at,!0)),1)]),_:2},1032,["props"])]),_:2},1032,["onClick"])]),_:1},8,["columns","rows","loading","pagination","onRequest"])]),_:1})]),_:1}),t(T,{modelValue:l.invite.open,"onUpdate:modelValue":a[15]||(a[15]=e=>l.invite.open=e)},{default:s(()=>[t(S,null,{default:s(()=>[t(P,null,{default:s(()=>[w("span",J,u(o.$t("pages.users.add.title")),1),N(t(g,{icon:"close",size:"12px",round:"",flat:""},null,512),[[C]])]),_:1}),t(r),t(c,{class:"row q-col-gutter-md"},{default:s(()=>[o.$user.type=="admin"?(v(),y(b,{key:0,class:"col-xs-12",label:o.$t("globals.user_fields.type"),options:o.lists.user_types.value,modelValue:l.invite.data.type,"onUpdate:modelValue":a[9]||(a[9]=e=>l.invite.data.type=e),"map-options":"","emit-value":"",validate:"type"},null,8,["label","options","modelValue"])):U("",!0),t(f,{class:"col-xs-12 col-sm-6",type:"first_name",modelValue:l.invite.data.first_name,"onUpdate:modelValue":a[10]||(a[10]=e=>l.invite.data.first_name=e),label:o.$t("globals.user_fields.first_name"),validate:"first_name"},null,8,["modelValue","label"]),t(f,{class:"col-xs-12 col-sm-6",type:"last_name",modelValue:l.invite.data.last_name,"onUpdate:modelValue":a[11]||(a[11]=e=>l.invite.data.last_name=e),label:o.$t("globals.user_fields.last_name"),validate:"last_name"},null,8,["modelValue","label"]),t(f,{class:"col-xs-12 col-sm-6",type:"email",modelValue:l.invite.data.email,"onUpdate:modelValue":a[12]||(a[12]=e=>l.invite.data.email=e),label:o.$t("globals.user_fields.email"),validate:"email"},null,8,["modelValue","label"]),t(f,{class:"col-xs-12 col-sm-6",modelValue:l.invite.data.phone,"onUpdate:modelValue":a[13]||(a[13]=e=>l.invite.data.phone=e),label:o.$t("globals.user_fields.phone"),validate:"phone"},null,8,["modelValue","label"]),t(h,{modelValue:l.invite.data.birth_date,"onUpdate:modelValue":a[14]||(a[14]=e=>l.invite.data.birth_date=e),class:"col-xs-12 col-sm-6",label:o.$t("globals.user_fields.birth_date"),validate:"birth_date"},null,8,["modelValue","label"])]),_:1}),t(r),t(c,{class:"row items-center justify-between"},{default:s(()=>[N(t(g,{flat:"",label:o.$t("globals.cancel")},null,8,["label"]),[[C]]),t(g,{color:"primary",label:o.$t("globals.add"),loading:l.invite.loading,onClick:l.invite_user},null,8,["label","loading","onClick"])]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})}var W=M(F,[["render",K]]);export{W as default};
