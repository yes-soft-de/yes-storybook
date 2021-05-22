import { ComponentFixture, TestBed } from '@angular/core/testing';

import { YesNavComponent } from './yes-nav.component';

describe('YesNavComponent', () => {
  let component: YesNavComponent;
  let fixture: ComponentFixture<YesNavComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ YesNavComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(YesNavComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
