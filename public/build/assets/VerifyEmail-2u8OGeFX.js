import{C as c,h as g,c as p,o as a,w as o,a as i,b as r,f as y,g as k,u as t,m as x,d as v,n as b,e as n,P as h}from"./app-DIrB7N0l.js";import{_}from"./GuestLayout-RBkzNMcl.js";import{P as w}from"./PrimaryButton-D52DXGRx.js";import"./ApplicationLogo-CqUnBoMG.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const V={key:0,class:"mb-4 text-sm font-medium text-green-600 dark:text-green-400"},B={class:"mt-4 flex items-center justify-between"},j={__name:"VerifyEmail",props:{status:{type:String}},setup(d){const u=d,s=c({}),l=()=>{s.post(route("verification.send"))},m=g(()=>u.status==="verification-link-sent");return(f,e)=>(a(),p(_,null,{default:o(()=>[i(t(x),{title:"Email Verification"}),e[2]||(e[2]=r("div",{class:"mb-4 text-sm text-gray-600 dark:text-gray-400"}," Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another. ",-1)),m.value?(a(),y("div",V," A new verification link has been sent to the email address you provided during registration. ")):k("",!0),r("form",{onSubmit:v(l,["prevent"])},[r("div",B,[i(w,{class:b({"opacity-25":t(s).processing}),disabled:t(s).processing},{default:o(()=>e[0]||(e[0]=[n(" Resend Verification Email ")])),_:1},8,["class","disabled"]),i(t(h),{href:f.route("logout"),method:"post",as:"button",class:"rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"},{default:o(()=>e[1]||(e[1]=[n("Log Out")])),_:1},8,["href"])])],32)]),_:1}))}};export{j as default};
