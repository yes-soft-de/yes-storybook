import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AuthFormComponent } from './ui/forms/auth-form/auth-form.component';
import { MatInputModule } from '@angular/material/input';
import { SharedModule } from '../shared/shared.module';



@NgModule({
  declarations: [
    AuthFormComponent
  ],
  imports: [
    CommonModule,
    MatInputModule,
    SharedModule
  ],
  exports: [
    AuthFormComponent
  ]
})
export class AuthModule { }
