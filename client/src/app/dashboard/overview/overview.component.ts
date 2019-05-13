import { Component, OnInit } from "@angular/core";
import { Router } from "@angular/router";
import { Auth } from "./../../auth.guard";

@Component({
	selector: "app-overview",
	templateUrl: "./overview.component.html",
	styleUrls: ["./overview.component.styl"]
})
export class DashboardOverviewComponent implements OnInit {
	constructor(private router: Router) {}

	ngOnInit() {}
}
