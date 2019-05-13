const threads = {
	init: () => {
		threads.getComments();
		// threads.login();
	},
	getComments: () => {
		let xhr = new XMLHttpRequest();
		xhr.open("POST", `http://localhost:8080/api/v2/comments/list.php`);
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.onreadystatechange = () => {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				let res = JSON.parse(xhr.responseText);
				if (xhr.status === 200) {
					if (res.comments.length === 0) {
						document.querySelector(
							"#threads"
						).innerHTML = threads.errors.noCommentsFound();
					} else {
						res.comments.forEach(ele => {
							console.log(ele);
						});
					}
				} else {
					document.querySelector(
						"#threads"
					).innerHTML = threads.errors.unableToLoadComments(res.error.message);
				}
			}
		};
		xhr.onerror = () => {
			document.querySelector("#threads").innerHTML = threads.errors.unableToLoadComments();
		};
		xhr.send();
	},
	parseToken: token => {
		return JSON.parse(
			window.atob(
				token
					.split(".")[1]
					.replace(/-/g, "+")
					.replace(/_/g, "/")
			)
		);
	},
	errors: {
		noCommentsFound: () => {
			return "No comments found for this URL.";
		},
		unableToLoadComments: message => {
			if (message) {
				return `Unable to load comments. ${message}. Try again later.`;
			} else {
				return "Unable to load comments. Try again later.";
			}
		}
	}
};

window.addEventListener("load", () => {
	document.querySelector(
		"#threads"
	).innerHTML = `Loading comments from <a href="https://threads.atanos.ga/">Threads</a>...`;
	threads.init();
});
