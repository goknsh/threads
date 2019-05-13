import { Injectable } from "@angular/core";
import { Router, CanActivate } from "@angular/router";
import { environment } from "./../environments/environment";

@Injectable()
export class AuthGuard implements CanActivate {
	constructor(private router: Router) {}

	canActivate() {
		if (localStorage.getItem("user::Token") !== null) {
			if (new Auth().verifyTokenValidity()) {
				return true;
			} else {
				this.router.navigate(environment.tokenExpiredRoute);
				return false;
			}
		} else {
			this.router.navigate(environment.unauthorizedRoute);
			return false;
		}
	}
}

export class Auth {
	private token: string = localStorage.getItem("user::Token");

	verifyTokenValidity() {
		let tokenData = this.parseToken();
		if (tokenData.exp > Math.floor(new Date().getTime() / 1000)) {
			return true;
		} else {
			return false;
		}
	}

	parseToken() {
		if (localStorage.getItem("user::Token") !== null) {
			return JSON.parse(
				window.atob(
					localStorage
						.getItem("user::Token")
						.split(".")[1]
						.replace(/-/g, "+")
						.replace(/_/g, "/")
				)
			);
		} else {
			return false;
		}
	}

	userData() {
		if (this.verifyTokenValidity()) {
			return JSON.parse(
				window.atob(
					localStorage
						.getItem("user::Token")
						.split(".")[1]
						.replace(/-/g, "+")
						.replace(/_/g, "/")
				)
			).data;
		} else {
			return false;
		}
	}
}
