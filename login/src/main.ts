// Import AngularJS
import "angular"
import "angular-ui-router"
import "../../webroot/js/lib/parseuri.js"

// Import Angular

import "./ng.login-app"
import "./controllers"
import "./directives"
import '@angular/compiler';
import 'zone.js';

import {DoBootstrap, NgModule} from "@angular/core";
import {BrowserModule} from "@angular/platform-browser";
import {UpgradeModule} from "@angular/upgrade/static";
import {HttpClientModule} from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import {platformBrowserDynamic} from "@angular/platform-browser-dynamic";
import {ToastService, AngularToastifyModule} from 'angular-toastify';

// Import CSS
import "bootstrap/dist/css/bootstrap.css"
import "@fortawesome/fontawesome-free/css/all.css"
import "noty/lib/noty.css"
import "noty/lib/themes/metroui.css"
import "./assets/css/index.css";
import {LoginLayoutComponent} from "./controllers/LoginLayout.component";
import {UsersLoginComponent} from "./controllers/UsersLogin.component";
import {TrueFalseValueDirective} from "./directives/TrueFalseValue.directive";

@NgModule({
    imports: [
        BrowserModule,
        UpgradeModule,
        HttpClientModule,
        FormsModule,
        AngularToastifyModule,
    ],
    providers: [
        ToastService
    ],
    declarations: [
        LoginLayoutComponent,
        UsersLoginComponent,
        TrueFalseValueDirective
    ],
    bootstrap: [
        LoginLayoutComponent,
        UsersLoginComponent,
    ]

})
export class AppModule implements DoBootstrap {
    // Override Angular bootstrap so it doesn't do anything
    ngDoBootstrap() {
    }

    //ngDoBootstrap() {
    //}
}

// Bootstrap using the UpgradeModule

platformBrowserDynamic().bootstrapModule(AppModule).then(platformRef => {
    console.log("Bootstrapping in Hybrid mode with Angular & AngularJS");
    const upgrade = platformRef.injector.get(UpgradeModule) as UpgradeModule;
    upgrade.bootstrap(document.body, ['openITCOCKPITLogin']);
});
