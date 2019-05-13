import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { AuthGuard } from "./auth.guard";

import { HomeComponent } from "./home/home.component";
import { UserLoginComponent } from "./user/login/login.component";
import { UserSignupComponent } from "./user/signup/signup.component";
import { DashboardCommentsComponent } from "./dashboard/comments/comments.component";
import { DashboardDomainsComponent } from "./dashboard/domains/domains.component";
import { DashboardOverviewComponent } from "./dashboard/overview/overview.component";
import { DashboardProfileComponent } from "./dashboard/profile/profile.component";
import { DashboardSettingsComponent } from "./dashboard/settings/settings.component";
import { PageNotFoundComponent } from "./page-not-found/page-not-found.component";

const routes: Routes = [
	{
		path: "user/login",
		pathMatch: "full",
		redirectTo: "user/login/new"
	},
	{
		path: "user/login/:reason",
		component: UserLoginComponent,
		data: { title: "login // threads", styles: { padding: false } }
	},
	{
		path: "user/signup",
		component: UserSignupComponent,
		data: { title: "signup // threads", styles: { padding: false } }
	},
	{
		path: "dashboard",
		pathMatch: "full",
		redirectTo: "dashboard/overview"
	},
	{
		path: "dashboard/comments",
		component: DashboardCommentsComponent,
		data: { title: "comments // dashboard // threads", styles: { padding: true } },
		canActivate: [AuthGuard]
	},
	{
		path: "dashboard/domains",
		component: DashboardDomainsComponent,
		data: { title: "domains // dashboard // threads", styles: { padding: true } },
		canActivate: [AuthGuard]
	},
	{
		path: "dashboard/overview",
		component: DashboardOverviewComponent,
		data: { title: "overview // dashboard // threads", styles: { padding: true } },
		canActivate: [AuthGuard]
	},
	{
		path: "dashboard/profile",
		component: DashboardProfileComponent,
		data: { title: "profile // dashboard // threads", styles: { padding: true } },
		canActivate: [AuthGuard]
	},
	{
		path: "dashboard/settings",
		component: DashboardSettingsComponent,
		data: { title: "settings // dashboard // threads", styles: { padding: true } },
		canActivate: [AuthGuard]
	},
	{
		path: "",
		component: HomeComponent,
		data: { title: "home // threads", styles: { padding: true } }
	},
	{
		path: "**",
		component: PageNotFoundComponent,
		data: { title: "page not found // threads", styles: { padding: true } }
	}
];

@NgModule({
	imports: [RouterModule.forRoot(routes)],
	exports: [RouterModule]
})
export class AppRoutingModule { }
