import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { YesNavComponent } from './ui/yes-nav/yes-nav.component';
import { SharedModule } from '../shared/shared.module';



@NgModule({
  declarations: [
    YesNavComponent
  ],
  imports: [
    CommonModule,
    SharedModule
  ],
  exports: [
    YesNavComponent
  ]
})
export class NavigationModule { }
