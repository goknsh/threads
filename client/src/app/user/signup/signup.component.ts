import { Component, OnInit } from "@angular/core";
import { Router, ActivatedRoute } from "@angular/router";
import { Title } from "@angular/platform-browser";
import { FormGroup, FormControl, Validators } from "@angular/forms";
import { HttpClient, HttpHeaders, HttpErrorResponse } from "@angular/common/http";
import { ClrLoadingState } from "@clr/angular";

import { environment } from "../../../environments/environment";

interface response {
	ok: boolean;
	token: string;
	response: string;
	error: any;
}

@Component({
	selector: "app-signup",
	templateUrl: "./signup.component.html",
	styleUrls: ["./signup.component.styl"]
})
export class UserSignupComponent implements OnInit {
	constructor(public router: Router, private route: ActivatedRoute, private http: HttpClient) { }

	public ok: boolean = true;
	public titleService: Title;
	public errorMessage: string = "An error has occured";
	public signupBtnState: ClrLoadingState = ClrLoadingState.DEFAULT;

	ngOnInit() {
		this.route.params.subscribe(params => {
			if (params.reason === "token-expired") {
				this.ok = false;
				this.errorMessage = "Please login again. Your session has expired.";
			}
			if (params.reason === "unauthorized") {
				this.ok = false;
				this.errorMessage =
					"The page your were trying to access requires you to be logged in.";
			}
			if (params.reason === "end") {
				this.ok = false;
				this.errorMessage = "You have logged out.";
				localStorage.removeItem("user::Token");
			}
		});
	}

	signupForm = new FormGroup({
		profPic: new FormControl(""),
		name: new FormControl("", [Validators.required]),
		email: new FormControl("", [Validators.required, Validators.email]),
		password: new FormControl("", [Validators.required])
	});

	signupFormSubmit() {
		this.signupBtnState = ClrLoadingState.LOADING;
		this.http
			.post<response>(
				`${environment.apiServer}/user/new.php`,
				JSON.stringify(this.signupForm.value)
			)
			.subscribe(
				data => {
					if (data.ok) {
						this.ok = data.ok;
						this.signupBtnState = ClrLoadingState.SUCCESS;
						localStorage.setItem("user::Token", data.token);
						if (localStorage.getItem("user::Token")) {
							this.router.navigate(["/dashboard/overview"]);
						}
					} else {
						this.ok = data.ok;
						this.signupBtnState = ClrLoadingState.DEFAULT;
						this.errorMessage = `${data.error.message}`;
					}
				},
				(err: HttpErrorResponse) => {
					this.ok = false;
					this.signupBtnState = ClrLoadingState.DEFAULT;
					this.errorMessage = `${err.error.error.message}`;
				}
			);
	}
}
