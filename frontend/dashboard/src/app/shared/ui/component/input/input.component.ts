import { Component, Input, OnInit } from '@angular/core';
import { FormControl } from '@angular/forms';

@Component({
  selector: 'yes-input',
  templateUrl: './input.component.html',
  styleUrls: ['./input.component.scss']
})
export class InputComponent implements OnInit {
  @Input() control = new FormControl('');
  @Input() containerClassName = '';
  @Input() iconSize = 16;
  @Input() placeholder = '';
  @Input() label = '';
  @Input() hint = '';
  @Input() enableClearButton = false;

  get isShowClearButton(): boolean {
    return this.enableClearButton && this.control?.value;
  }

  constructor() { }

  ngOnInit(): void {
    this.control = this.control ?? new FormControl('');
  }

  clear(): void {
    this.control.patchValue('');
  }
}
