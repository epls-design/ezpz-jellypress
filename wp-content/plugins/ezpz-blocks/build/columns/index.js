(()=>{"use strict";var e,t={760:()=>{const e=window.wp.element,t=window.wp.blocks,n=window.wp.primitives,r=(0,e.createElement)(n.SVG,{viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},(0,e.createElement)(n.Path,{d:"M19 6H6c-1.1 0-2 .9-2 2v9c0 1.1.9 2 2 2h13c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-4.1 1.5v10H10v-10h4.9zM5.5 17V8c0-.3.2-.5.5-.5h2.5v10H6c-.3 0-.5-.2-.5-.5zm14 0c0 .3-.2.5-.5.5h-2.6v-10H19c.3 0 .5.2.5.5v9z"})),o=(window.wp.i18n,window.wp.blockEditor),c=JSON.parse('{"u2":"ezpz/columns"}');(0,t.registerBlockType)(c.u2,{icon:{src:(0,e.createElement)((function({icon:t,size:n=24,...r}){return(0,e.cloneElement)(t,{width:n,height:n,...r})}),{icon:r})},edit:function(){let t=(0,o.useBlockProps)(),n=t.className.split(" ");n.forEach((e=>{e.match(/has-(.*)-background-color/)&&(n.push("bg-"+RegExp.$1),n=n.filter((t=>t!==e)))})),n.some((e=>e.match(/bg-/)))||n.push("bg-white"),n.push("block"),t.className=n.join(" ");const r=(0,o.useInnerBlocksProps)(t,{allowedBlocks:["ezpz/column"],template:[["ezpz/column",{}],["ezpz/column",{}]]});return(0,e.createElement)("section",{...t},(0,e.createElement)("div",{className:"container"},(0,e.createElement)("div",{className:"row"},r.children)))},save:t=>(0,e.createElement)("section",{...o.useBlockProps.save()},(0,e.createElement)("div",{className:"container"},(0,e.createElement)("div",{className:"row"},(0,e.createElement)(o.InnerBlocks.Content,null))))})}},n={};function r(e){var o=n[e];if(void 0!==o)return o.exports;var c=n[e]={exports:{}};return t[e](c,c.exports,r),c.exports}r.m=t,e=[],r.O=(t,n,o,c)=>{if(!n){var l=1/0;for(p=0;p<e.length;p++){n=e[p][0],o=e[p][1],c=e[p][2];for(var s=!0,a=0;a<n.length;a++)(!1&c||l>=c)&&Object.keys(r.O).every((e=>r.O[e](n[a])))?n.splice(a--,1):(s=!1,c<l&&(l=c));if(s){e.splice(p--,1);var i=o();void 0!==i&&(t=i)}}return t}c=c||0;for(var p=e.length;p>0&&e[p-1][2]>c;p--)e[p]=e[p-1];e[p]=[n,o,c]},r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={454:0,829:0};r.O.j=t=>0===e[t];var t=(t,n)=>{var o,c,l=n[0],s=n[1],a=n[2],i=0;if(l.some((t=>0!==e[t]))){for(o in s)r.o(s,o)&&(r.m[o]=s[o]);if(a)var p=a(r)}for(t&&t(n);i<l.length;i++)c=l[i],r.o(e,c)&&e[c]&&e[c][0](),e[c]=0;return r.O(p)},n=self.webpackChunkezpz_blocks=self.webpackChunkezpz_blocks||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))})();var o=r.O(void 0,[829],(()=>r(760)));o=r.O(o)})();