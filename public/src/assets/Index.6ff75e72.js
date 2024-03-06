import{j as R,a6 as Y,a7 as G,k as E,l as V,a3 as J,aM as W,bz as X,bA as Z,bl as $,m as aa,R as ea,T as la,_ as ta,p as Q,b6 as s,x as oa,v as na,r as u,o as p,c as y,w as d,ai as o,b2 as C,aT as k,n as sa,b3 as q,d as z,b4 as M,b5 as x,aU as T,b7 as U,aY as N}from"./index.c5c56c9d.js";import{C as j}from"./ClosePopup.a9596800.js";var ia=R({name:"QBreadcrumbsEl",props:{...Y,label:String,icon:String,tag:{type:String,default:"span"}},emits:["click"],setup(a,{slots:l}){const{linkTag:m,linkAttrs:e,linkClass:f,navigateOnClick:w}=G(),c=E(()=>({class:"q-breadcrumbs__el q-link flex inline items-center relative-position "+(a.disable!==!0?"q-link--focusable"+f.value:"q-breadcrumbs__el--disable"),...e.value,onClick:w})),b=E(()=>"q-breadcrumbs__el-icon"+(a.label!==void 0?" q-breadcrumbs__el-icon--with-label":""));return()=>{const r=[];return a.icon!==void 0&&r.push(V(J,{class:b.value,name:a.icon})),a.label!==void 0&&r.push(a.label),V(m.value,{...c.value},W(l.default,r))}}});const da=["",!0];var ra=R({name:"QBreadcrumbs",props:{...X,separator:{type:String,default:"/"},separatorColor:String,activeColor:{type:String,default:"primary"},gutter:{type:String,validator:a=>["none","xs","sm","md","lg","xl"].includes(a),default:"sm"}},setup(a,{slots:l}){const m=Z(a),e=E(()=>`flex items-center ${m.value}${a.gutter==="none"?"":` q-gutter-${a.gutter}`}`),f=E(()=>a.separatorColor?` text-${a.separatorColor}`:""),w=E(()=>` text-${a.activeColor}`);return()=>{const c=$(aa(l.default));if(c.length===0)return;let b=1;const r=[],_=c.filter(i=>i.type!==void 0&&i.type.name==="QBreadcrumbsEl").length,t=l.separator!==void 0?l.separator:()=>a.separator;return c.forEach(i=>{if(i.type!==void 0&&i.type.name==="QBreadcrumbsEl"){const g=b<_,h=i.props!==null&&da.includes(i.props.disable),S=(g===!0?"":" q-breadcrumbs--last")+(h!==!0&&g===!0?w.value:"");b++,r.push(V("div",{class:`flex items-center${S}`},[i])),g===!0&&r.push(V("div",{class:"q-breadcrumbs__separator"+f.value},t()))}else r.push(i)}),V("div",{class:"q-breadcrumbs"},[V("div",{class:e.value},r)])}}});function ca(a){const l=document.createElement("textarea");l.value=a,l.contentEditable="true",l.style.position="fixed";const m=()=>{};ea(m),document.body.appendChild(l),l.focus(),l.select();const e=document.execCommand("copy");return l.remove(),la(m),e}function ua(a){return navigator.clipboard!==void 0?navigator.clipboard.writeText(a):new Promise((l,m)=>{const e=ca(a);e?l(!0):m(e)})}const ga={setup(){const a=Q({language:s.state.user.language,id:null,path:null,list:[],loading:!1}),l=Q({show:!1,loading:!1,selected_language:"en",data:{id:null,language:null,parent_id:null,type:"value",label:"",key:"",value:s.spreadLanguages()}}),m=[{name:"type",align:"left",label:"",field:"type",sortable:!0,style:"width: 20px",headerStyle:"width: 20px"},{name:"key",align:"left",label:s.locale.t("pages.localizations.key"),field:"key",sortable:!0},{name:"label",align:"left",label:s.locale.t("pages.localizations.label"),field:"label",sortable:!0},{name:"value",align:"left",label:s.locale.t("pages.localizations.value"),field:"value",sortable:!0},{name:"actions",align:"right",label:"",field:"actions",sortable:!1}],e=[{value:"value",label:s.locale.t("pages.localizations.value")},{value:"node",label:s.locale.t("pages.localizations.node")}];oa(()=>{const t=s.router.currentRoute.value.params.id;t&&(a.value.id=t,l.value.data.parent_id=t),f()}),na(s.router.currentRoute,t=>{const i=t.params.id;i!=a.value.id&&(a.value.id=i,l.value.data.parent_id=i,f())});async function f(){let t=null;if(a.value.loading=!0,a.value.id?t=await s.store.dispatch("localization/show",{id:a.value.id,language:a.value.language}):t=await s.store.dispatch("localization/index"),t&&t.status){a.value.list=t.localizations;let i=[{id:null,key:"root"}];t.path?a.value.path=[...i,...t.path]:a.value.path=i}a.value.loading=!1}async function w(){s.clearErrors(),l.value.loading=!0;let t=null;l.value.data.id?t=await s.store.dispatch("localization/update",l.value.data):t=await s.store.dispatch("localization/store",l.value.data),t.status?(l.value.show=!1,f()):t.statusCode==422&&s.setErrors(t==null?void 0:t.errors),l.value.loading=!1}function c(t=null){l.value.show=!0,l.value.loading=!1,s.clearErrors(),l.value.selected_language="en",t?l.value.data={...t}:l.value.data={id:null,language:a.value.language,parent_id:a.value.id,type:"value",label:"",key:"",value:s.spreadLanguages()}}function b(t,i=null){let g=a.value.path.filter(h=>h.id).map(h=>h.key);i&&g.push(i),g=g.join("."),t.ctrlKey?g=`{{ $t('${g}') }}`:t.shiftKey&&(g=`$t('${g}')`),ua(g)}function r(t){async function i(){(await s.store.dispatch("localization/destroy",t.id)).status&&await f()}s.$q.dialog({title:s.locale.t("globals.confirm"),message:s.locale.t("pages.localizations.delete"),cancel:s.locale.t("globals.cancel"),ok:s.locale.t("globals.yes")}).onOk(async()=>{t.type=="node"?s.$q.dialog({title:s.locale.t("globals.please_note"),message:s.locale.t("pages.localizations.delete_node")}).onOk(i):i()})}async function _(){a.value.sync_loading=!0,(await s.store.dispatch("localization/sync")).status?s.$q.notify({type:"positive",message:s.locale.t("pages.localizations.synced")}):s.$q.notify({type:"negative",message:s.locale.t("pages.localizations.sync_failed")}),a.value.sync_loading=!1}return{state:a,dialog:l,columns:m,localization_types:e,save:w,destroy:r,sync:_,show_dialog:c,copy_path:b}}},pa={class:"text-h6 q-mr-auto"},ma={class:"row items-center q-gap-sm"},fa={class:"row items-center q-gap-sm q-mr-auto"},ba={class:"no-wrap row justify-end q-gap-sm"},_a={class:"text-h6"},va={key:0,class:"text-negative inline-block q-mb-md"},ya={class:"row no-wrap full-width q-gap-md"};function ha(a,l,m,e,f,w){const c=u("ap-button"),b=u("ap-select"),r=u("ap-card-section"),_=u("ap-separator"),t=u("ap-icon"),i=u("ap-td"),g=u("ap-tr"),h=u("ap-table"),S=u("ap-card"),A=u("ap-dialog-header"),L=u("ap-input"),F=u("ap-tab"),O=u("ap-tabs"),D=u("ap-editor"),H=u("ap-dialog-card"),I=u("ap-dialog"),K=u("ap-page");return p(),y(K,null,{default:d(()=>[o(S,null,{default:d(()=>[o(r,{class:"row items-center q-gap-md"},{default:d(()=>[C("span",pa,k(a.$t("pages.localizations.title")),1),C("div",ma,[C("div",fa,[o(c,{size:"sm",label:a.$t("globals.sync"),color:"primary",onClick:e.sync},null,8,["label","onClick"]),o(c,{size:"sm",label:a.$t("globals.create"),color:"primary",onClick:l[0]||(l[0]=n=>e.show_dialog(null))},null,8,["label"])])]),o(b,{"map-options":"","emit-value":"",options:a.lists.languages,modelValue:e.state.language,"onUpdate:modelValue":l[1]||(l[1]=n=>e.state.language=n),class:sa({"full-width":a.$q.screen.lt.sm})},null,8,["options","modelValue","class"])]),_:1}),o(_),o(r,{class:"q-py-sm text-bold row items-center q-gap-sm"},{default:d(()=>{var n;return[q(k(a.$t("pages.localizations.path"))+" ",1),o(ra,{separator:"/"},{default:d(()=>[(p(!0),z(x,null,M(e.state.path,v=>(p(),y(ia,{key:v.key,label:v.key,to:{name:"localizations.index",params:{id:v.id}}},null,8,["label","to"]))),128))]),_:1}),((n=e.state.path)==null?void 0:n.length)>1?(p(),y(c,{key:0,icon:"content_copy",size:"xs",round:"",onClick:e.copy_path},null,8,["onClick"])):T("",!0)]}),_:1}),o(_),o(r,{"no-padding":"",class:"overflow-hidden"},{default:d(()=>[o(h,{"hide-bottom":"",flat:"",columns:e.columns,rows:e.state.list,color:"primary",loading:e.state.loading,pagination:{rowsPerPage:1e3}},{body:d(n=>[o(g,{onClick:v=>n.row.type=="node"?a.$router.push({name:"localizations.index",params:{id:n.row.id}}):e.show_dialog(n.row)},{default:d(()=>{var v,P;return[o(i,{key:"type",class:"cursor-pointer"},{default:d(()=>[n.row.type=="value"?(p(),y(t,{key:0,name:"sym_o_line_start_diamond",size:"22px",color:"primary"})):(p(),y(t,{key:1,name:"sym_o_network_node",size:"22px",color:"primary"}))]),_:2},1024),o(i,{key:"key"},{default:d(()=>[q(k(n.row.key),1)]),_:2},1024),o(i,{key:"label"},{default:d(()=>[q(k(n.row.label),1)]),_:2},1024),o(i,{key:"value",innerHTML:((P=n.row.value)==null?void 0:P[(v=e.state)==null?void 0:v.language])||""},null,8,["innerHTML"]),o(i,{key:"actions"},{default:d(()=>[C("div",ba,[o(c,{icon:"content_copy",size:"xs",round:"",onClick:U(B=>e.copy_path(B,n.row.key),["stop"])},null,8,["onClick"]),o(c,{icon:"edit",size:"xs",round:"",onClick:U(B=>e.show_dialog(n.row),["stop"])},null,8,["onClick"]),o(c,{icon:"delete",color:"negative",size:"xs",round:"",onClick:U(B=>e.destroy(n.row),["stop"])},null,8,["onClick"])])]),_:2},1024)]}),_:2},1032,["onClick"])]),_:1},8,["columns","rows","loading"])]),_:1})]),_:1}),o(I,{modelValue:e.dialog.show,"onUpdate:modelValue":l[7]||(l[7]=n=>e.dialog.show=n)},{default:d(()=>[o(H,{style:{"max-width":"700px"}},{default:d(()=>[o(A,null,{default:d(()=>[C("span",_a,[e.dialog.data.id?(p(),z(x,{key:0},[q(k(a.$t("globals.edit")),1)],64)):(p(),z(x,{key:1},[q(k(a.$t("globals.create")),1)],64))]),N(o(c,{flat:"",round:"",class:"q-ml-auto",size:"sm",icon:"close"},null,512),[[j]])]),_:1}),o(_),o(r,{class:"column"},{default:d(()=>[e.dialog.data.id&&e.dialog.data.type=="node"?(p(),z("span",va,k(a.$t("pages.localizations.node_edit")),1)):T("",!0),C("div",ya,[e.dialog.data.id==null?(p(),y(b,{key:0,class:"full-width",label:a.$t("pages.localizations.type"),"map-options":"","emit-value":"",options:e.localization_types,modelValue:e.dialog.data.type,"onUpdate:modelValue":l[2]||(l[2]=n=>e.dialog.data.type=n),validate:"type"},null,8,["label","options","modelValue"])):T("",!0),o(L,{class:"full-width",label:a.$t("pages.localizations.key"),modelValue:e.dialog.data.key,"onUpdate:modelValue":l[3]||(l[3]=n=>e.dialog.data.key=n),validate:"key"},null,8,["label","modelValue"]),o(L,{class:"full-width",label:a.$t("pages.localizations.label"),modelValue:e.dialog.data.label,"onUpdate:modelValue":l[4]||(l[4]=n=>e.dialog.data.label=n),validate:"label"},null,8,["label","modelValue"])])]),_:1}),e.dialog.data.type=="value"?(p(),z(x,{key:0},[o(_),o(r,{"no-padding":""},{default:d(()=>[o(O,{dense:"",modelValue:e.dialog.selected_language,"onUpdate:modelValue":l[5]||(l[5]=n=>e.dialog.selected_language=n),class:"full-width",align:"justify"},{default:d(()=>[(p(!0),z(x,null,M(a.lists.languages,(n,v)=>(p(),y(F,{key:v,"no-caps":!1,name:n.value,label:n.value},null,8,["name","label"]))),128))]),_:1},8,["modelValue"])]),_:1}),o(r,{"no-padding":""},{default:d(()=>[o(D,{rich:"","no-focus":"",modelValue:e.dialog.data.value[e.dialog.selected_language],"onUpdate:modelValue":l[6]||(l[6]=n=>e.dialog.data.value[e.dialog.selected_language]=n),"min-height":"10rem"},null,8,["modelValue"])]),_:1})],64)):(p(),y(_,{key:1})),o(r,{class:"row items-center justify-between"},{default:d(()=>[N(o(c,{flat:"",label:a.$t("globals.cancel"),color:"primary"},null,8,["label"]),[[j]]),o(c,{label:a.$t("globals.save"),color:"primary",loading:e.dialog.loading,onClick:e.save},null,8,["label","loading","onClick"])]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})}var Ca=ta(ga,[["render",ha]]);export{Ca as default};
