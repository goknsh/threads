import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DashboardDomainsComponent } from './domains.component';

describe('DashboardDomainsComponent', () => {
	let component: DashboardDomainsComponent;
	let fixture: ComponentFixture<DashboardDomainsComponent>;

	beforeEach(async(() => {
		TestBed.configureTestingModule({
			declarations: [DashboardDomainsComponent]
		})
			.compileComponents();
	}));

	beforeEach(() => {
		fixture = TestBed.createComponent(DashboardDomainsComponent);
		component = fixture.componentInstance;
		fixture.detectChanges();
	});

	it('should create', () => {
		expect(component).toBeTruthy();
	});
});
