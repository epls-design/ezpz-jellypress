(()=>{"use strict";const e=window.wp.blocks,t=window.React,n=window.wp.primitives,o=(0,t.createElement)(n.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,t.createElement)(n.Path,{d:"m4 5.5h2v6.5h1.5v-6.5h2v-1.5h-5.5zm16 10.5h-16v-1.5h16zm-7 4h-9v-1.5h9z"})),i=window.wp.blockEditor,l=window.wp.i18n,p=window.wp.coreData,w=JSON.parse('{"u2":"ezpz/post-title"}');(0,e.registerBlockType)(w.u2,{icon:o,edit:function({context:{postType:e,postId:n}}){const[o="",w,r]=(0,p.useEntityProp)("postType",e,"title",n);let a=(0,t.createElement)("h1",null,(0,l.__)("Title","ezpz-post-title"));return e&&n&&(a=(0,t.createElement)(i.PlainText,{tagName:"h1",placeholder:(0,l.__)("No Title","ezpz-post-title"),value:o,onChange:w,__experimentalVersion:2})),(0,t.createElement)(t.Fragment,null,a)},save:()=>null})})();