(()=>{"use strict";var e={d:(t,o)=>{for(var s in o)e.o(o,s)&&!e.o(t,s)&&Object.defineProperty(t,s,{enumerable:!0,get:o[s]})},o:(e,t)=>Object.prototype.hasOwnProperty.call(e,t),r:e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})}},t={};e.r(t),e.d(t,{instanceOf:()=>y,isGenerator:()=>o,isModelEntity:()=>n,isModelEntityFactory:()=>i,isModelEntityFactoryOfModel:()=>r,isModelEntityOfModel:()=>a,isSchema:()=>l,isSchemaOfModel:()=>m,isSchemaResponse:()=>c,isSchemaResponseOfModel:()=>d});const o=e=>!!e&&"Generator"===e[Symbol.toStringTag],s=window.lodash,i=e=>!!e&&!(0,s.isUndefined)(e.classDef)&&!(0,s.isUndefined)(e.modelName)&&"BaseEntity"===Object.getPrototypeOf(e.classDef).name,r=(e,t)=>i(e)&&e.modelName===t,n=e=>(0,s.isObject)(e)&&"BaseEntity"===Object.getPrototypeOf(e.constructor).name,a=(e,t)=>(t=(0,s.upperFirst)((0,s.camelCase)(t)),n(e)&&e.constructor.name===t),c=e=>f(e)&&l(e.schema),l=e=>(0,s.isPlainObject)(e)&&!(0,s.isUndefined)(e.$schema)&&(0,s.isPlainObject)(e.properties),d=(e,t)=>f(e)&&m(e.schema,t),m=(e,t)=>l(e)&&!(0,s.isUndefined)(e.title)&&(0,s.lowerCase)(t)===(0,s.lowerCase)(e.title),f=e=>(0,s.isPlainObject)(e)&&!(0,s.isUndefined)(e.schema);function y(e,t){if(!e)return!1;if(e.constructor){if(e.constructor.name&&e.constructor.name===t)return!0;if(e.constructor.displayName&&e.constructor.displayName===t)return!0}return e.hasOwnProperty("displayName")&&e.displayName===t}(this.eejs=this.eejs||{}).validators=t})();