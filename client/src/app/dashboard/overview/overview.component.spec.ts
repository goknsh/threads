import { async, ComponentFixture, TestBed } from "@angular/core/testing";

import { DashboardOverviewComponent } from "./overview.component";

describe("DashboardOverviewComponent", () => {
	let component: DashboardOverviewComponent;
	let fixture: ComponentFixture<DashboardOverviewComponent>;

	beforeEach(async(() => {
		TestBed.configureTestingModule({
			declarations: [DashboardOverviewComponent]
		}).compileComponents();
	}));

	beforeEach(() => {
		fixture = TestBed.createComponent(DashboardOverviewComponent);
		component = fixture.componentInstance;
		fixture.detectChanges();
	});

	it("should create", () => {
		expect(component).toBeTruthy();
	});
});
