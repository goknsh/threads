import { Component, OnInit } from "@angular/core";
import { Router, NavigationEnd } from "@angular/router";
import { Title } from "@angular/platform-browser";
import { Auth } from "./auth.guard";

import { environment } from "./../environments/environment";

@Component({
	selector: "app-root",
	templateUrl: "./app.component.html",
	styleUrls: ["./app.component.styl"]
})
export class AppComponent implements OnInit {
	constructor(public router: Router, public titleService: Title) {}

	public routerData: any;
	public userExists: boolean = false;
	public user: any = {
		profile_picture: ""
	};
	public environment: any = environment;

	ngOnInit() {
		const auth = new Auth();
		this.router.events.subscribe((change: any) => {
			if (change instanceof NavigationEnd) {
				this.routerData = this.router.routerState.snapshot.root.firstChild.data;
				this.titleService.setTitle(this.routerData.title);
				setTimeout(() => {
					if (auth.userData()) {
						this.user = auth.userData();
						this.userExists = true;
					} else {
						this.userExists = false;
					}
				}, 50);
			}
		});
	}
}
