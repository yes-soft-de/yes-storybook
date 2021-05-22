import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { YesNavComponent } from './ui/yes-nav/yes-nav.component';
import { PageComponent } from './ui/page/page.component';
import { SharedModule } from '../shared/shared.module';


@NgModule({
  declarations: [
    YesNavComponent,
    PageComponent
  ],
  imports: [
    CommonModule,
    SharedModule
  ],
  exports: [
    YesNavComponent,
    PageComponent
  ]
})
export class NavigationModule { }
