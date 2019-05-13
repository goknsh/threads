import { BrowserModule, Title } from "@angular/platform-browser";
import { NgModule } from "@angular/core";
import { ReactiveFormsModule } from "@angular/forms";
import { HttpClientModule } from "@angular/common/http";
import { AuthGuard } from "./auth.guard";

import { AppRoutingModule } from "./app-routing.module";
import { AppComponent } from "./app.component";
import { ClarityModule } from "@clr/angular";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { PageNotFoundComponent } from "./page-not-found/page-not-found.component";
import { UserLoginComponent } from "./user/login/login.component";
import { UserSignupComponent } from "./user/signup/signup.component";
import { HomeComponent } from "./home/home.component";
import { DashboardOverviewComponent } from "./dashboard/overview/overview.component";
import { DashboardProfileComponent } from './dashboard/profile/profile.component';
import { DashboardDomainsComponent } from './dashboard/domains/domains.component';
import { DashboardCommentsComponent } from './dashboard/comments/comments.component';
import { DashboardSettingsComponent } from './dashboard/settings/settings.component';

@NgModule({
	declarations: [
		AppComponent,
		PageNotFoundComponent,
		UserLoginComponent,
		UserSignupComponent,
		HomeComponent,
		DashboardOverviewComponent,
		DashboardProfileComponent,
		DashboardDomainsComponent,
		DashboardCommentsComponent,
		DashboardSettingsComponent
	],
	imports: [
		BrowserModule,
		ReactiveFormsModule,
		HttpClientModule,
		AppRoutingModule,
		ClarityModule,
		BrowserAnimationsModule
	],
	providers: [AuthGuard, Title],
	bootstrap: [AppComponent]
})
export class AppModule { }
