/**
 * Copyright 2015 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
#contbtn{
	text-align: center;
	font-size: 18px;
	font-weight: 700;
	color: #007569;
	background-color: #f5f5f5;
	height: 60%;
    margin: 20px 0;
}
#contbtn:hover{
	background-color: #FFC107;
	color: #fff;
	
}


#contdiv{
	padding: 10px;
    text-align: center;
    background: #007569;
}
#discontbtn{
	cursor: unset;
    text-align: center;
    font-size: 20px;
    font-weight: 700;
    height: 60%;
    color: #dedede;
    background-color: #009485;
    margin: 25px 0 0 0;
}
#tabing{
	margin-bottom: 10px; 
	max-width: 730px; 
	height: 40px;
}
html, body {
  /*font-family: 'Open Sans',Helvetica,Arial,sans-serif;*/
  font-family: "Helvetica Neue",HelveticaNeueRoman,Helvetica,Arial,sans-serif!important;
  margin: 0;
  padding: 0;
}
.mdl-demo .mdl-layout__header-row {
  padding-left: 40px;
}
.mdl-demo .mdl-layout.is-small-screen .mdl-layout__header-row h3 {
  font-size: inherit;
}
.mdl-demo .mdl-layout__tab-bar-button {
  display: none;
}
.mdl-demo .mdl-layout.is-small-screen .mdl-layout__tab-bar .mdl-button {
  display: none;
}
.mdl-demo .mdl-layout:not(.is-small-screen) .mdl-layout__tab-bar,
.mdl-demo .mdl-layout:not(.is-small-screen) .mdl-layout__tab-bar-container {
  overflow: visible;
}
.mdl-demo .mdl-layout__tab-bar-container {
  height: 64px;
}
.mdl-demo .mdl-layout__tab-bar {
  padding: 0;
  padding-left: 16px;
  box-sizing: border-box;
  height: 100%;
  width: 100%;
}
.mdl-demo .mdl-layout__tab-bar .mdl-layout__tab {
  height: 64px;
  line-height: 64px;
}
.mdl-demo .mdl-layout__tab-bar .mdl-layout__tab.is-active::after {
  background-color: white;
  height: 4px;
}
.mdl-demo main > .mdl-layout__tab-panel {
  padding: 8px;
  padding-top: 48px;
}
.mdl-demo .mdl-card {
  height: auto;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-flex-direction: column;
      -ms-flex-direction: column;
          flex-direction: column;
}
.mdl-demo .mdl-card > * {
  height: auto;
}
.mdl-demo .mdl-card .mdl-card__supporting-text {
  margin: 40px;
  -webkit-flex-grow: 1;
      -ms-flex-positive: 1;
          flex-grow: 1;
  padding: 0;
  color: inherit;
  width: calc(100% - 80px);
}
.mdl-demo.mdl-demo .mdl-card__supporting-text h4 {
  margin-top: 0;
  margin-bottom: 20px;
}
.mdl-demo .mdl-card__actions {
  margin: 0;
  padding: 4px 40px;
  color: inherit;
}
.mdl-demo .mdl-card__actions a {
  color: #00BCD4;
  margin: 0;
}
.mdl-demo .mdl-card__actions a:hover,
.mdl-demo .mdl-card__actions a:active {
  color: inherit;
  background-color: transparent;
}
.mdl-demo .mdl-card__supporting-text + .mdl-card__actions {
  border-top: 1px solid rgba(0, 0, 0, 0.12);
}
.mdl-demo #add {
  position: absolute;
  right: 40px;
  top: 36px;
  z-index: 999;
}

.mdl-demo .mdl-layout__content section:not(:last-of-type) {
  position: relative;
  margin-bottom: 48px;
}
.mdl-demo section.section--center {
  max-width: 860px;
}
.mdl-demo #features section.section--center {
  max-width: 620px;
}
.mdl-demo section > header{
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-align-items: center;
      -ms-flex-align: center;
          align-items: center;
  -webkit-justify-content: center;
      -ms-flex-pack: center;
          justify-content: center;
}
.mdl-demo section > .section__play-btn {
  min-height: 200px;
}
.mdl-demo section > header > .material-icons {
  font-size: 3rem;
}
.mdl-demo section > button {
  position: absolute;
  z-index: 99;
  top: 8px;
  right: 8px;
}
.mdl-demo section .section__circle {
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-align-items: center;
      -ms-flex-align: center;
          align-items: center;
  -webkit-justify-content: flex-start;
      -ms-flex-pack: start;
          justify-content: flex-start;
  -webkit-flex-grow: 0;
      -ms-flex-positive: 0;
          flex-grow: 0;
  -webkit-flex-shrink: 1;
      -ms-flex-negative: 1;
          flex-shrink: 1;
}
.mdl-demo section .section__text {
  -webkit-flex-grow: 1;
      -ms-flex-positive: 1;
          flex-grow: 1;
  -webkit-flex-shrink: 0;
      -ms-flex-negative: 0;
          flex-shrink: 0;
  padding-top: 8px;
}
.mdl-demo section .section__text h5 {
  font-size: inherit;
  margin: 0;
  margin-bottom: 0.5em;
}
.mdl-demo section .section__text a {
  text-decoration: none;
}
.mdl-demo section .section__circle-container > .section__circle-container__circle {
  width: 64px;
  height: 64px;
  border-radius: 32px;
  margin: 8px 0;
}
.mdl-demo section.section--footer .section__circle--big {
  width: 100px;
  height: 100px;
  border-radius: 50px;
  margin: 8px 32px;
}
.mdl-demo .is-small-screen section.section--footer .section__circle--big {
  width: 50px;
  height: 50px;
  border-radius: 25px;
  margin: 8px 16px;
}
.mdl-demo section.section--footer {
  padding: 64px 0;
  margin: 0 -8px -8px -8px;
}
.mdl-demo section.section--center .section__text:not(:last-child) {
  border-bottom: 1px solid rgba(0,0,0,.13);
}
.mdl-demo .mdl-card .mdl-card__supporting-text > h3:first-child {
  margin-bottom: 24px;
}
.mdl-demo .mdl-layout__tab-panel:not(#overview) {
  background-color: white;
}
.mdl-demo #features section {
  margin-bottom: 72px;
}
.mdl-demo #features h4, #features h5 {
  margin-bottom: 16px;
}
.mdl-demo .toc {
  border-left: 4px solid #C1EEF4;
  margin: 24px;
  padding: 0;
  padding-left: 8px;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-flex-direction: column;
      -ms-flex-direction: column;
          flex-direction: column;
}
.mdl-demo .toc h4 {
  font-size: 0.9rem;
  margin-top: 0;
}
.mdl-demo .toc a {
  color: #4DD0E1;
  text-decoration: none;
  font-size: 16px;
  line-height: 28px;
  display: block;
}

mdl-layout__header-row{
      height: 0px;
}
.mdl-color--primary  {
background-color: unset;
}



.activeTab{
	min-height: 40px;
	text-align: center;
	padding-top: 10px;
	text-transform: uppercase;
	cursor: pointer;
	box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}
.currentSpan{
	width: 100%;
	background: #7cb342;
	height: 20%;
	margin: 27px 0;
	position: absolute;
}
.otherTab{
	background: #fdfdfd;
	min-height: 40px;
	text-align: center;
	padding-top: 10px;
	text-transform: uppercase;
	cursor: pointer;
	box-shadow:0 0px 0px 0 rgba(0,0,0,.14), 0 0px 0px 0px rgba(0,0,0,.2),
	-1px 0px 0px 0 rgba(0,0,0,.12)
}
.otherTab:hover {
    background-color: #ddd;
}
.updatesLogo{
	width: 100%;
	margin: 0px -50px;
	position: absolute;
}
.fbShare{
	background: #3b5998;
	color: #fff;
	min-height: 40px;
	text-align: center;
	padding-top: 10px;
	text-transform: uppercase;
	cursor: pointer;
	box-shadow:0 0px 0px 0 rgba(0,0,0,.14), 0 0px 0px 0px rgba(165, 165, 165, 0.12), 
	-1px 0px 0px 0 rgba(165, 165, 165, 0.12)
}
.twtShare{
	background: #3ea2f1;
	color: #fff;
		min-height: 40px;
	text-align: center;
	padding-top: 10px;
	text-transform: uppercase;
	cursor: pointer;
	box-shadow:0 0px 0px 0 rgba(0,0,0,.14), 0 0px 0px 0px rgba(0,0,0,.2),
	-1px 0px 0px 0 rgba(165, 165, 165, 0.12)
}
@media (max-width: 839px){
	.twtShare{
		opacity: 0;
		display: none;
    }
	.profileInfo{
		opacity: 0;
		display: none;
    }
	.leftSidePanel{
		opacity: 0;
		display: none;
    }
	.fbShare{
		opacity: 0;
		display: none;
    }
	.rightSidePanel{
		opacity: 0;
		display: none;
    }
	.mdl-dialog{
		width: 100%;
	}
}
@media (min-width: 840px){
	.mdl-dialog{
		width: 39%;
	}
}
.loader {
	margin: 0 auto;
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #007569; /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
.profile{
	box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
					height: 100px;
    width: 100px;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    border-radius: 100px;
}
.avatars{
	box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
	height: 40px;
    width: 40px;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    border-radius: 100px;
    float: left;
    margin-right: 10px;
    margin-top: 5px;
}
.leftSidePanel{
	width: 21%;
    position: fixed;
    color: #006157;
    font-size: 15px;
	padding: 7% 0.2% 0px 4%;
}
.rightSidePanel{
	width: 19%;
    height: 100%;
    position: fixed;
    right: 20px;
	padding: 7% 0.2% 0px 0.2%;
}
.gallery{
	border-radius: 4px;
    cursor: pointer;
    float: left;
    height: 83px;
    margin: 5px 0 0 5px;
    overflow: hidden;
    position: relative;
    width: 83px;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
}

#sendMoney{
	width:500px;
	display: none;
	padding:0px;
	border-radius: 4px
}
.sendMoneyProgress{
	width: 100%;
	height: 54px
}
.dialogHeader{
	border-radius: 4px 4px 0 0;
	padding: 10px;
	text-align: center;
	display: block;
	font-size: 20px;
	background: #fff;
	color: #000;
}
.progressTab{
	width: 33.33333333%;
	height: 100%;
	float:left;
	color: #fff;
	position: relative;
	padding: 12px 20px;
	margin: 0;
	vertical-align: top
}
.proTabActive{
	background: #00897b
}
.proTabNormal{
	background-color: #eee;
}
.proTabLeft{
	background-color: #4caf50;
}
.step-number{
	position: absolute;
	top: 50%;
	left: 20px;
	width: 30px;
	height: 30px;
	font-size: 24px;
	line-height: 30px;
	text-align: center;
	border-radius: 50%;
	transform: translateY(-50%)
}
.num-active{
	color: #00897b;
	background-color: #fff
}
.num-normal{
	color: #fff;
	background-color: #bdbdbd
}
.step-desc{
	min-height: 30px;
	margin-left: 40px;
	text-align: left
}
.step-title{
	font-size: 18px;
	margin-bottom: 0
}
.stepActive{
	color: #fff;
}
.stepNormal{
	color: #bdbdbd;
}
.input-field{
	-webkit-appearance: none;
	padding: 6px 13px;
	width: 45%;
	font-size: 20px;
	border-radius: 200px;
	height: 36px;
    border: 1px solid #e0e0e0;
}
.transferBtn{
	width: 25%;
	height:75px;
	padding: 0 8px;
	transition: all 280ms cubic-bezier(0.4, 0, 0.2, 1);
}
.transferBtn:hover{
	height:75px;
	padding: 0 7.5px
}
.payBtn{
	cursor: pointer;
	border-radius: 4px; 
	background-size: 100% 100%;
	height: 100%; 
	width: 100%; 
	margin: 0 auto; 
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
}
.payBtn:hover{
	box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.12), 0px 2px 7px rgba(0, 0, 0, 0.24)
}
.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
		}
.progress-bar{
	background-color: #2196f3
}
.mdl-color--grey-100 {
    background-color: #dde0e0 !important;
}
.navDiv{
	margin-left: auto;
	margin-right: auto;
	max-width: 1200px;
	padding: 0 35px;
	color: #444
}
.signinBtn{
	color: #fff;
	float: right;
	background: #2196F3;
	margin-top: 14px;
	border: none;
	border-radius: 2px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
	min-height: 31px;
	min-width: 70px;
	padding: 2px 16px;
	text-align: center;
	text-shadow: none;
	text-transform: uppercase;
	box-sizing: border-box;
	cursor: pointer;
	-webkit-appearance: none;
	display: inline-block;
	vertical-align: middle;
	font: 500 14px/31px 'Roboto', sans-serif !important;
}
.groupMedia{
	display: block; margin-top: 20px;
}
.groupMediaTitle{
	color: #657786;font-size: 14px;line-height: 1;margin-bottom: 10px;
}

.contSection{
	margin-top: -140px;
	margin-bottom: 20px !important;
	max-width: 100% !important;
	z-index: 20;
}
.fundTitle{
	
}
.fundName{
	color: #f5f5f5; 
	font-size: 28px;
}
.fundDesc{
	font-size: 18px; 
	color: #e1eae9;
}
.fundImg{
	background-size: cover;
	background-repeat: no-repeat;
	background-position: center center; 
	height: 380px; 
	width: 100%;
}
.titleOverlay{
	background: linear-gradient(to bottom,transparent 0,rgba(0,0,0,.82) 100%);
    text-shadow: 2px 2px 14px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    top: 255px;
    padding: 0 15px;
    position: absolute;
    width: 100%;
    height: 33%;
	z-index: 10;
}

.midlePage{
	width: 60%;
	margin-left: 0px; 
	float: left;
	padding-top: 50px; 
}
.navDiv{
	height: 64px;
	padding-top: 7px;
}
.mdl-layout__header{
	background: #007569;
}


.logo{
	    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    height: 50px;
    width: 50px;
    border-radius: 100px;
    margin: auto;
    background-color: #fff;
    cursor: pointer;
}
.sharing{
	display: none;
	/*background-color: red;*/
    float: left;
    height: 100%;
    padding: 8px 10px;
}
.shareicon{
	color: #fff;
    font-size: 18px;
    cursor: pointer;
}
.contshare{
    text-align: center;
}

@media (max-width: 839px) {
	.uk-navbar{
		text-align: center;
	}
	#contbtn{
	font-size: 19px;
    height: 100%;
	margin:0;
	padding-top: 0px;
    }
	.contSection{
		margin: -82px 0 0 0 !important;
		margin-bottom: 20px !important;
		
	}
	.fundTitle{
		position: relative;
		top: 76px;
	}
	.fundDesc{
		opacity:0;
	}
	.fundName{
		font-size: 23px;
	}
	.fundImg{
		top: 14px;
		position: relative;
	}
	.titleOverlay{
		top: 255px;
		height: 37%;
	}
	#tabing{
		margin-bottom: 0px;
	}
	.progress{
		margin-bottom: 10px;
	}
	.contribution{
		position: fixed;
		bottom: 0;
	}
	.midlePage{
		width: 100%;
	}
	.sharing{
		display: block;
		width: 15%;
	}
	.contshare{
		float: left;
    	width: 70%;
	}

}

